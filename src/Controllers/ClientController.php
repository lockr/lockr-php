<?php
namespace Lockr\Controllers;

use InvalidArgumentException;

use Lockr\LoaderInterface;
use Lockr\Model\Client;

class ClientController
{
    /** @var LoaderInterface $loader */
    private $loader;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param array $params
     *
     * @return Client
     */
    public function createFromCsr(array $params)
    {
        if (!isset($params['csr_text'])) {
            throw new InvalidArgumentException('csr_text param is required');
        }
        if (!isset($params['client_token'])) {
            throw new InvalidArgumentException('client_token param is required');
        }
        $data = [
            'type' => 'client',
            'attributes' => [
                'csr_text' => $params['csr_text'],
            ],
            'relationships' => [
                'client_token' => [
                    'data' => [
                        'type' => 'client-token',
                        'id' => $params['client_token'],
                    ],
                ],
            ],
        ];
        return $this->loader->create($data);
    }
}

// ex: ts=4 sts=4 sw=4 et:
