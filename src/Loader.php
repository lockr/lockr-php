<?php
namespace Lockr;

use Exception;

use GuzzleHttp;
use GuzzleHttp\Psr7;

use Lockr\Model;

class Loader implements LoaderInterface
{
    /** @var GuzzleHttp\ClientInterface $httpClient */
    private $httpClient;

    /** @var array $cache */
    private $cache = [];

    /** @var array $modelMap */
    private $modelMap = [
        'client' => Model\Client::class,
        'client-token' => Model\ClientToken::class,
        'site' => Model\Site::class,
    ];

    /** @var array $routeMap */
    private $routeMap = [
        'client' => 'clients',
        'client-token' => 'client-tokens',
        'site' => 'sites',
    ];

    /**
     * @param GuzzleHttp\ClientInterface $http_client
     */
    public function __construct(GuzzleHttp\ClientInterface $http_client)
    {
        $this->httpClient = $http_client;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $uri = "/{$this->routeMap[$data['type']]}";
        $headers = [
            'content-type' => ['application/api+json'],
        ];
        $body = json_encode(['data' => $data]);
        $req = new Psr7\Request('POST', $uri, $headers, $body);
        $resp = $this->httpClient->send($req);
        $status = $resp->getStatusCode();
        if ($status >= 500) {
            throw new Exception('server error');
        } else if ($status >= 400) {
            throw new Exception('client error');
        }
        $body = json_decode((string) $resp->getBody(), true);
        return $this->loadModel($body['data']);
    }

    /**
     * {@inheritdoc}
     */
    public function load($type, $id)
    {
        $key = "{$type}:{$id}";
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }
        $uri = "/{$this->routeMap[$data['type']]}/{$id}";
        $req = new Psr7\Request('GET', $uri);
        $resp = $this->httpClient->send($req);
        if ($status >= 500) {
            throw new Exception('server error');
        } else if ($status >= 400) {
            throw new Exception('client error');
        }
        $body = json_decode((string) $resp->getBody(), true);
        return $this->loadModel($body['data']);
    }

    /**
     * @param array $data
     *
     * @returns ModelInterface
     */
    private function loadModel(array $data)
    {
        $type = $data['type'];
        $cls = $this->modelMap[$type];
        $model = new $cls($data);
        $this->cache["{$type}:{$data['id']}"] = $model;
        return $model;
    }
}

// ex: ts=4 sts=4 sw=4 et:
