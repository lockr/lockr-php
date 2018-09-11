<?php
namespace Lockr\Controllers;

use InvalidArgumentException;

use Lockr\LoaderInterface;
use Lockr\Model\Site;

class SiteController
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
     * @returns Site
     */
    public function create(array $params)
    {
        if (!isset($params['label'])) {
            throw new InvalidArgumentException('label param is required');
        }
        $data = [
            'type' => 'site',
            'attributes' => [
                'label' => $params['label'],
            ],
        ];
        return $this->loader->create($data);
    }
}

// ex: ts=4 sts=4 sw=4 et:
