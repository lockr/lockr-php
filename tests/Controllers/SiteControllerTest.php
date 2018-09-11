<?php
namespace Lockr\Tests\Controllers;

use DateTime;

use PHPUnit\Framework\TestCase;

use Lockr\Controllers\SiteController;
use Lockr\LoaderInterface;
use Lockr\Model\Site;

class SiteControllerTest extends TestCase
{
    public function testCreate()
    {
        $label = 'Test Label';
        $data = [
            'type' => 'site',
            'attributes' => [
                'label' => $label,
            ],
        ];
        $now = new DateTime();
        $now_fmt = $now->format(DateTime::RFC3339);
        $site = new Site([
            'type' => 'site',
            'id' => 'aaa',
            'attributes' => [
                'created' => $now_fmt,
                'modified' => $now_fmt,
                'label' => $label,
            ],
        ]);
        $loader = $this->createMock(LoaderInterface::class);
        $loader->method('create')
            ->with($data)
            ->willReturn($site);

        $controller = new SiteController($loader);
        $this->assertSame($site, $controller->create(['label' => $label]));
    }
}

// ex: ts=4 sts=4 sw=4 et:
