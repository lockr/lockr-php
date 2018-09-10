<?php
namespace Lockr\Tests;

use PHPUnit\Framework\TestCase;

use Lockr\CertSettings;

class CertSettingsTest extends TestCase
{
    public function testCertOption()
    {
        $settings = new CertSettings('/etc/private/cert.pem');
        $this->assertSame(
            ['cert' => '/etc/private/cert.pem'],
            $settings->getOptions()
        );
    }
}

// ex: ts=4 sts=4 sw=4 et:
