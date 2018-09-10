<?php
namespace Lockr\Tests\Model;

use Datetime;

use PHPUnit\Framework\TestCase;

use Lockr\Model\Site;

class SiteTest extends TestCase
{
    public function testCreated()
    {
        $site = $this->site();
        $this->assertInstanceOf(DateTime::class, $site->getCreated());
    }

    public function testModified()
    {
        $site = $this->site();
        $this->assertInstanceOf(DateTime::class, $site->getModified());
    }

    public function testLabel()
    {
        $site = $this->site();
        $this->assertSame('Test Label', $site->getLabel());
    }

    public function site()
    {
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        return new Site([
            'type' => 'site',
            'id' => 'aaa',
            'attributes' => [
                'created' => $now_fmt,
                'modified' => $now_fmt,
                'label' => 'Test Label',
            ],
        ]);
    }
}

// ex: ts=4 sts=4 sw=4 et:
