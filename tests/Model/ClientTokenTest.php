<?php
namespace Lockr\Tests\Model;

use DateTime;

use PHPUnit\Framework\TestCase;

use Lockr\Model\ClientToken;

class ClientTokenTest extends TestCase
{
    public function testCreated()
    {
        $ct = $this->ct();
        $this->assertInstanceOf(DateTime::class, $ct->getCreated());
    }

    public function testEnv()
    {
        $ct = $this->ct();
        $this->assertSame('dev', $ct->getEnv());
    }

    public function testSite()
    {
        $ct = $this->ct();
        $site = [
            'links' => [
                'self' => '/client-tokens/aaa/relationships/site',
                'related' => '/client-tokens/aaa/site',
            ],
        ];
        $this->assertSame($site, $ct->getSite());
    }

    public function ct()
    {
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        return new ClientToken([
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
    }
}

// ex: ts=4 sts=4 sw=4 et:
