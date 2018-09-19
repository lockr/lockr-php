<?php
namespace Lockr;

use GuzzleHttp;

use Lockr\Controllers;
use Lockr\Guzzle\MiddlewareFactory;

class Lockr
{
    /** @const VERSION string */
    const VERSION = 'dev';

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
     * @return Controllers\ClientController
     */
    public function clients()
    {
        return new Controllers\ClientController($this->loader);
    }

    /**
     * @return Controllers\ClientTokenController
     */
    public function clientTokens()
    {
        return new Controllers\ClientTokenController($this->loader);
    }

    /**
     * @return Controllers\SecretController
     */
    public function secrets()
    {
        return new Controllers\SecretController($this->loader);
    }

    /**
     * @return Controllers\SecretValueController
     */
    public function secretValues()
    {
        return new Controllers\SecretValueController($this->loader);
    }

    /**
     * @return Controllers\SiteController
     */
    public function sites()
    {
        return new Controllers\SiteController($this->loader);
    }
}

// ex: ts=4 sts=4 sw=4 et:
