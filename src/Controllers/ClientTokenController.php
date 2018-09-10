<?php
namespace Lockr\Controllers;

use InvalidArgumentException;

use Lockr\LoaderInterface;
use Lockr\Model\ClientToken;

class ClientTokenController
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
     * @returns ClientToken;
     */
    public function create(array $params)
    {
        if (!isset($params['env'])) {
            throw new InvalidArgumentException('env param is required');
        }
        if (!isset($params['site'])) {
            throw new InvalidArgumentException('site param is required');
        }
        $data = [
            'type' => 'client-token',
            'attributes' => [
                'env' => $params['env'],
            ],
            'relationships' => [
                'site' => [
                    'data' => [
                        'type' => 'site',
                        'id' => $params['site'],
                    ],
                ],
            ],
        ];
        return $this->loader->create('client-tokens', $data);
    }
}

// ex: ts=4 sts=4 sw=4 et:
