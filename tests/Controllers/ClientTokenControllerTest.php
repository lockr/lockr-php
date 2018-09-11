<?php
namespace Lockr\Tests\Controllers;

use DateTime;

use PHPUnit\Framework\TestCase;

use Lockr\Controllers\ClientTokenController;
use Lockr\LoaderInterface;
use Lockr\Model\ClientToken;

class ClientTokenControllerTest extends TestCase
{
    public function testCreate()
    {
        $data = [
            'type' => 'client-token',
            'attributes' => [
                'env' => 'dev',
            ],
            'relationships' => [
                'site' => [
                    'data' => [
                        'type' => 'site',
                        'id' => 'bbb',
                    ],
                ],
            ],
        ];
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        $client_token = new ClientToken([
            'type' => 'client-token',
            'id' => 'aaa',
            'attributes' => [
                'created' => $now_fmt,
                'env' => 'dev',
            ],
            'relationships' => [
                'site' => [
                    'links' => [
                        'self' => '/client-tokens/aaa/relationships/site',
                        'related' => '/client-tokens/aaa/site',
                    ],
                ],
            ],
        ]);
        $loader = $this->createMock(LoaderInterface::class);
        $loader->method('create')
            ->with($data)
            ->willReturn($client_token);

        $controller = new ClientTokenController($loader);
        $this->assertSame(
            $client_token,
            $controller->create([
                'env' => 'dev',
                'site' => 'bbb',
            ])
        );
    }
}

// ex: ts=4 sts=4 sw=4 et:
