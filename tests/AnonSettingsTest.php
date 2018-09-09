<?php
namespace Lockr\Tests;

use PHPUnit\Framework\TestCase;

use Lockr\AnonSettings;

class AnonSettingsTest extends TestCase
{
    public function testDefaults()
    {
        $settings = new AnonSettings();
        $this->assertSame('api.lockr.io', $settings->getHostname());
        $this->assertSame([], $settings->getOptions());
    }

    public function testFormatsSovereignty()
    {
        $settings = new AnonSettings('us');
        $this->assertSame('us.api.lockr.io', $settings->getHostname());
    }
}

// ex: ts=4 sts=4 sw=4 et:
