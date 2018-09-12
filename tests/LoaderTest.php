<?php
namespace Lockr\Tests;

use DateTime;

use GuzzleHttp;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7;

use PHPUnit\Framework\TestCase;

use Lockr\Loader;
use Lockr\Model;
use Lockr\ModelInterface;

class LoaderTest extends TestCase
{
    public function testCreate()
    {
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);

        $mock = new MockHandler([
            new Psr7\Response(
                201,
                ['content-type' => 'application/api+json'],
                json_encode([
                    'data' => [
                        'type' => 'site',
                        'id' => 'aaa',
                        'attributes' => [
                            'created' => $now_fmt,
                            'modified' => $now_fmt,
                            'label' => 'Test Label',
                        ],
                    ],
                ])
            ),
        ]);
        $handler = GuzzleHttp\HandlerStack::create($mock);
        $client = new GuzzleHttp\Client(['handler' => $handler]);
        $loader = new Loader($client);

        $data = [
            'type' => 'site',
            'attributes' => [
                'label' => 'Test Label',
            ],
        ];
        $site = $loader->create($data);
        $this->assertInstanceOf(Model\Site::class, $site);
        $this->assertSame('aaa', $site->getId());
        $this->assertSame('Test Label', $site->getLabel());
    }

    public function testLoadCache()
    {
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        $mock = new MockHandler([
            new Psr7\Response(
                200,
                ['content-type' => 'application/api+json'],
                json_encode([
                    'data' => [
                        'type' => 'site',
                        'id' => 'aaa',
                        'attributes' => [
                            'created' => $now_fmt,
                            'modified' => $now_fmt,
                            'label' => 'Test Label',
                        ],
                    ],
                ])
            ),
        ]);
        $handler = GuzzleHttp\HandlerStack::create($mock);
        $client = new GuzzleHttp\Client(['handler' => $handler]);
        $loader = new Loader($client);
        $site = $loader->load('site', 'aaa');
        $cached_site = $loader->load('site', 'aaa');
        $this->assertSame($site, $cached_site);
    }

    public function testLoadRelated()
    {
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        $mock = new MockHandler([
            new Psr7\Response(
                200,
                ['content-type' => 'application/api+json'],
                json_encode([
                    'data' => [
                        'type' => 'site',
                        'id' => 'aaa',
                        'attributes' => [
                            'created' => $now_fmt,
                            'modified' => $now_fmt,
                            'label' => 'Test Label',
                        ],
                    ],
                ])
            ),
        ]);
        $handler = GuzzleHttp\HandlerStack::create($mock);
        $client = new GuzzleHttp\Client(['handler' => $handler]);
        $loader = new Loader($client);
        $model = $this->createMock(ModelInterface::class);
        $model->method('getRelationship')
            ->with('site')
            ->willReturn([
                'links' => [
                    'related' => '/clients/bbb/site',
                ],
            ]);
        $site = $loader->loadRelated($model, 'site');
        $this->assertSame('aaa', $site->getId());
    }
}

// ex: ts=4 sts=4 sw=4 et:
