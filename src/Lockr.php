<?php
namespace Lockr;

use RuntimeException;

use Guzzle\Psr7;
use Symfony\Component\Yaml\Yaml;

use Lockr\KeyWrapper\MultiKeyWrapper;

class Lockr
{
    /** @var LockrClient $client */
    protected $client;

    /** @var SecretInfoInterface $info */
    private $info;

    /** @var string $accountsHost */
    private $accountsHost;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(
        LockrClient $client,
        SecretInfoInterface $secret_info,
        $accounts_host = 'accounts.lockr.io'
    ) {
        $this->client = $client;
        $this->info = $secret_info;
        $this->accountsHost = $accounts_host;
    }

    public function createCertClient($client_token, array $dn)
    {
        $key = openssl_pkey_new(['private_key_bits' => 2048]);
        if ($key === false) {
            throw new RuntimeException('Could not create private key.');
        }
        if (!openssl_pkey_export($key, $key_text)) {
            throw new RuntimeException('Could not export private key.');
        }
        $csr = openssl_csr_new($dn, $key);
        if ($csr === false) {
            throw new RuntimeException('Could not create CSR.');
        }
        if (!openssl_csr_export($csr, $csr_text)) {
            throw new RuntimeException('Could not export CSR.');
        }

        $query = <<<EOQ
mutation CreateCertClient($input: CreateCertClient!) {
  createCertClient(input: $input) {
    auth {
      ... on LockrCert {
        certText
      }
    }
  }
}
EOQ;
        $data = $this->client->query([
            'query' => $query,
            'variables' => [
                'input' => [
                    'token' => $client_token,
                    'csrText' => $csr_text,
                ],
            ],
        ]);
        return [
            'key_text' => $key_text,
            'cert_text' => $data['createCertClient']['auth']['certText'],
        ];
    }

    public function createPantheonClient($client_token)
    {
        $query = <<<EOQ
mutation CreatePantheonClient($input: CreatePantheonClient!) {
  createPantheonClient(input: $input) {
    id
  }
}
EOQ;
        $this->client->query([
            'query' => $query,
            'variables' => [
                'input' => [
                    'token' => $client_token,
                ],
            ],
        ]);
    }

    /**
     * Gets client info
     *
     * @return array
     */
    public function getClient()
    {
        $query = <<<EOQ
{
    self {
        env
        label
        keyring {
            label
            hasCC
            trialEnd
        }
    }
}
EOQ;
        return $this->client->query(['query' => $query]);
    }

    /**
     * Creates a secret value by name.
     *
     * @param string $name
     * @param string $value
     * @param string|null $label
     *
     * @return string
     */
    public function createSecretValue($name, $value, $label = null)
    {
        $info = $this->info->getSecretInfo($name);
        if (isset($info['wrapping_key'])) {
            $ret = MultiKeyWrapper::reencrypt($value, $info['wrapping_key']);
        }
        else {
            $ret = MultiKeyWrapper::encrypt($value);
        }
        $info['wrapping_key'] = $ret['encoded'];
        $value = $ret['ciphertext'];
        $query = <<<EOQ
mutation EnsureSecret($input: EnsureSecretValue!) {
  ensureSecretValue(input: $input) {
    id
  }
}
EOQ;
        if (is_null($label)) {
            $label = '';
        }
        $data = $this->client->query([
            'query' => $query,
            'variables' => [
                'input' => [
                    'name' => $name,
                    'label' => $label,
                    'value' => base64_encode($value),
                ],
            ],
        ]);
        return $data['ensureSecretValue']['id'];
    }

    /**
     * Gets the latest value of a secret by name.
     *
     * @param string $name
     * @param string|null $secret_value_id
     *
     * @return array
     */
    public function getSecretValue($name, $secret_value_id = null)
    {
        if (!$secret_value_id) {
            $svals = $this->loader->loadCollection(
                'secret-value',
                [
                    'filter[secret.name]' => $name,
                    'page[limit]' => 1,
                ]
            );
            if (!$svals) {
                return null;
            }
            $sval = $svals[0];
            $secret_value_id = $sval->getId();
        }
        else {
            $sval = $this->loader->load('secret-value', $secret_value_id);
        }
        $value = $sval->getValue();
        $info = $this->info->getSecretInfo($name);
        if (isset($info['wrapping_key'])) {
            $wk = $info['wrapping_key'];
            $value = MultiKeyWrapper::decrypt($value, $wk);
        }
        return [
            'secret_value_id' => $sval->getid(),
            'value' => $value,
        ];
    }

    /**
     * Exports secret data to YAML.
     *
     * @return string
     */
    public function exportSecretData()
    {
        $data = $this->info->getAllSecretInfo();
        return Yaml::dump($data, 2, 2);
    }

    /**
     * Imports secret data from YAML.
     *
     * @param string $info_yaml
     */
    public function importSecretData($info_yaml)
    {
        $data = Yaml::parse($info_yaml);
        foreach ($data as $name => $info) {
            $this->info->setSecretInfo($name, $info);
        }
    }

    /**
     * Allows programmatic registration of new sites.
     *
     * @param string $email
     * @param string $password
     * @param string $site_label
     * @param string $client_label
     */
    public function createSite($email, $password, $site_label, $client_label)
    {
        $uri = (new Psr7\Uri())
            ->withScheme('https')
            ->withHost($this->accountsHost)
            ->withPath('/lockr-api/register');
        $data = [
            'email' => $email,
            'password' => $password,
            'site_label' => $site_label,
            'client_label' => $client_label,
        ];
        $req = new Psr7\Request(
            'POST',
            $uri,
            [
                'content-type' => ['application/json'],
                'accept' => ['application/json'],
            ],
            json_encode($data)
        );
        $resp = $this->loader->getHttpClient()->send($req);
        return json_decode((string) $resp->getBody(), true);
    }
}

// ex: ts=4 sts=4 sw=4 et:
