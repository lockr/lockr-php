<?php
namespace Lockr;

use DateTime;

use GuzzleHttp;
use GuzzleHttp\Psr7;

class LockrAdmin
{
    /** @var LockrClient $client */
    protected $client;

    /**
     * @param LockrClient $client
     */
    public function __construct(LockrClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $label
     * @param bool $has_cc
     * @param DateTime $trial_end
     *
     * @return string
     */
    public function createKeyring($label, $has_cc, DateTime $trial_end)
    {
        $query = <<<EOQ
mutation CreateKeyring($input: CreateKeyring!) {
    createKeyring(input: $input) {
        id
    }
}
EOQ;
        $data = $this->client->query([
            'query' => $query,
            'variables' => [
                'input' => [
                    'label' => $label,
                    'hasCC' => $has_cc,
                    'trialEnd' => $trial_end->format(DateTime::RFC3339),
                ],
            ],
        ]);
        return $data['createKeyring']['id'];
    }

    /**
     * @param string $label
     * @param string $keyring_id
     * @param string $env
     *
     * @return string
     */
    public function createClientToken($label, $keyring_id, $env)
    {
        $query = <<<EOQ
mutation CreateClientToken($input: CreateClientToken!) {
  createClientToken(input: $input) {
    token
  }
}
EOQ;
        $data = $this->client->query([
            'query' => $query,
            'variables' => [
                'input' => [
                    'keyringId': $keyring_id,
                    'clientLabel' => $label,
                    'clientEnv' => $env,
                ],
            ],
        ]);
        return $data['createClientToken']['token'];
    }
}

// ex: ts=4 sts=4 sw=4 et:
