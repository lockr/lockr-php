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

    /** @var array $modelMap */
    private $modelMap = [
        'site' => Model\Site::class,
        'client-token' => Model\ClientToken::class,
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
    public function create($collection, array $data)
    {
        $uri = "/{$collection}";
        $headers = [
            'content-type' => 'application/api+json',
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
        $data = $body['data'];
        $cls = $this->modelMap[$data['type']];
        return new $cls($data);
    }
}

// ex: ts=4 sts=4 sw=4 et:
