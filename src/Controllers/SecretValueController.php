<?php
namespace Lockr\Controllers;

use InvalidArgumentException;

use Lockr\LoaderInterface;
use Lockr\Model\SecretValue;

class SecretValueController
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
     * @returns SecretValue
     */
    public function create(array $params)
    {
        if (!isset($params['secret'])) {
            throw new InvalidArgumentException('secret param is required');
        }
        if (!isset($params['value'])) {
            throw new InvalidArgumentException('value param is required');
        }
        $data = [
            'type' => 'secret-value',
            'attributes' => [
                'value' => base64_encode($params['value']),
            ],
            'relationships' => [
                'secret' => [
                    'data' => [
                        'type' => 'secret',
                        'id' => $params['secret'],
                    ],
                ],
            ],
        ];
        return $this->loader->create($data);
    }
}

// ex: ts=4 sts=4 sw=4 et:
