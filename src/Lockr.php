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
     * @param SettingsInterface $settings
     */
    public function __construct(SettingsInterface $settings)
    {
        $ua = 'php/' . phpversion() . ' LockrClient/' . self::VERSION;
        $handler = GuzzleHttp\HandlerStack::create();
        $handler->push(MiddlewareFactory::retry());
        $base_options = [
            'base_uri' => 'https://{$settings->getHostname()}',
            'handler' => $handler,
            'connect_timeout' => 2.0,
            'expect' => false,
            'headers' => [
                'accept' => ['application/api+json'],
                'user-agent' => [$ua],
            ],
            'http_errors' => false,
            'read_timeout' => 3.0,
            'timeout' => 5.0,
        ];
        $options = array_replace($base_options, $settings->getOptions());
        $client = new GuzzleHttp\Client($options);

        $this->loader = new Loader($client);
    }

    /**
     * @returns Controllers\ClientController
     */
    public function clients()
    {
        return new Controllers\ClientController($this->loader);
    }

    /**
     * @returns Controllers\ClientTokenController
     */
    public function clientTokens()
    {
        return new Controllers\ClientTokenController($this->loader);
    }

    /**
     * @returns Controllers\SecretController
     */
    public function secrets()
    {
        return new Controllers\SecretController($this->loader);
    }

    /**
     * @returns Controllers\SecretValueController
     */
    public function secretValues()
    {
        return new Controllers\SecretValueController($this->loader);
    }

    /**
     * @returns Controllers\SiteController
     */
    public function sites()
    {
        return new Controllers\SiteController($this->loader);
    }
}

// ex: ts=4 sts=4 sw=4 et:
