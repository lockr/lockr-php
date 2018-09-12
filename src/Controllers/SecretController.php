<?php
namespace Lockr\Controllers;

use InvalidArgumentException;

use Lockr\LoaderInterface;
use Lockr\Model\Secret;

class SecretController
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
     * @returns Secret
     */
    public function create(array $params)
    {
        if (!isset($params['name'])) {
            throw new InvalidArgumentException('name param is required');
        }
        if (!isset($params['label'])) {
            throw new InvalidArgumentException('label param is required');
        }
        if (!isset($params['policy'])) {
            throw new InvalidArgumentException('policy param is required');
        }
        if (!isset($params['sovereignty'])) {
            throw new InvalidArgumentException('sovereignty param is required');
        }
        if (!isset($params['site'])) {
            throw new InvalidArgumentException('site param is required');
        }
        $data = [
            'type' => 'secret',
            'attributes' => [
                'name' => $params['name'],
                'label' => $params['label'],
                'policy' => $pararms['policy'],
                'sovereignty' => $params['sovereignty'],
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
    }
}

// ex: ts=4 sts=4 sw=4 et:
