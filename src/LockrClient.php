<?php
namespace Lockr;

use RuntimeException;

use Symfony\Component\Yaml\Yaml;

use Lockr\KeyWrapper\MultiKeyWrapper;

class LockrClient
{
    /** @var LoaderInterface $loader */
    private $loader;

    /** @var SecretInfoInterface $info */
    private $info;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(
        LoaderInterface $loader,
        SecretInfoInterface $secret_info
    ) {
        $this->loader = $loader;
        $this->info = $secret_info;
    }

    public function createClient($client_token_id)
    {
        $this->loader->create([
            'type' => 'client',
            'relationships' => [
                'client_token' => [
                    'data' => [
                        'type' => 'client-token',
                        'id' => $client_token_id,
                    ],
                ],
            ],
        ]);
    }

    public function createClientCert($client_token_id, array $dn)
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
        $client = $this->loader->create([
            'type' => 'client',
            'attributes' => [
                'csr_text' => $csr_text,
            ],
            'relationships' => [
                'client_token' => [
                    'data' => [
                        'type' => 'client-token',
                        'id' => $client_token_id,
                    ],
                ],
            ],
        ]);
        $rel = $client->getRelationship('certs');
        $data = $rel['data'][0];
        $cert = $this->loader->load($data['type'], $data['id']);
        return [
            'key_text' => $key_text,
            'cert_text' => $cert->getCertText(),
        ];
    }

    /**
     * Gets this client.
     *
     * @return Model\Client
     */
    public function getClient()
    {
        return $this->loader->load('client', '_self', ['site']);
    }

    /**
     * Gets the site associated with this client.
     *
     * @return Model\Site
     */
    public function getSite()
    {
        $client = $this->getClient();
        return $this->loader->loadRelated($client, 'site');
    }

    /**
     * Gets client and site in a single call.
     *
     * @return array
     */
    public function getClientSite()
    {
        $client = $this->getClient();
        $site = $this->loader->loadRelated($client, 'site');
        return ['client' => $client, 'site' => $site];
    }

    /**
     * Creates a secret value.
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
        if (!isset($info['secret_id'])) {
            $secrets = $this->loader->loadCollection('secret');
            $secret = NULL;
            foreach ($secrets as $s) {
                if ($s->getName() === $name) {
                    $secret = $s;
                    break;
                }
            }
            if (!$secret) {
                $secret = $this->loader->create([
                    'type' => 'secret',
                    'attributes' => [
                        'name' => $name,
                        'label' => $label ?: $name,
                        'policy' => 'standard',
                    ],
                ]);
            }
            $info['secret_id'] = $secret->getId();
        }
        if (isset($info['wrapping_key'])) {
            $ret = MultiKeyWrapper::reencrypt($value, $info['wrapping_key']);
        }
        else {
            $ret = MultiKeyWrapper::encrypt($value);
        }
        $info['wrapping_key'] = $ret['encoded'];
        $value = $ret['ciphertext'];
        $sval = $this->loader->create([
            'type' => 'secret-value',
            'attributes' => [
                'value' => base64_encode($value),
            ],
            'relationships' => [
                'secret' => [
                    'data' => [
                        'type' => 'secret',
                        'id' => $info['secret_id'],
                    ],
                ],
            ],
        ]);
        $this->info->setSecretInfo($name, $info);
        return $sval->getId();
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
}

// ex: ts=4 sts=4 sw=4 et:
