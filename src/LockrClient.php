<?php
namespace Lockr;

use GuzzleHttp;
use GuzzleHttp\Psr7;

use Lockr\Exception\LockrApiException;
use Lockr\Guzzle\MiddlewareFactory;

class LockrClient
{
    const VERSION = 'dev';

    /** @var GuzzleHttp\ClientInterface $httpClient */
    private $httpClient;

    /**
     * @param GuzzleHttp\ClientInterface $http_client
     */
    public function __construct(GuzzleHttp\ClientInterface $http_client)
    {
        $this->httpClient = $http_client;
    }

    /**
     * @param SettingsInterface $settings
     */
    public static function createFromSettings(SettingsInterface $settings)
    {
        $ua = 'php/' . phpversion() . ' LockrClient/' . self::VERSION;
        $base_options = [
            'base_uri' => "https://{$settings->getHostname()}",
            'connect_timeout' => 2.0,
            'expect' => false,
            'headers' => [
                'accept' => ['application/json'],
                'user-agent' => [$ua],
            ],
            'http_errors' => false,
            'read_timeout' => 3.0,
            'timeout' => 5.0,
        ];
        $options = array_replace($base_options, $settings->getOptions());
        $client = new GuzzleHttp\Client($options);
        return new static($client);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function query(array $data)
    {
        $headers = [
            'content-type' => ['application/json'],
        ];
        $body = json_encode($data);
        $req = new Psr7\Request('POST', '/graphql', $headers, $body);
        $resp = $this->httpClient->send($req);
        $resp_data = json_decode((string) $resp->getBody(), true);
        if (!empty($resp_data['errors'])) {
            throw new LockrApiException($resp_data['errors']);
        }
        return $resp_data['data'];
    }
}

// ex: ts=4 sts=4 sw=4 et:
