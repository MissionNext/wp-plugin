<?php

use MissionNext\lib\SiteConfig;

class SiteConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $o = new SiteConfig();

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }

    public function testIsAgencyOn()
    {
        $o = new SiteConfig();

        $this->assertTrue($o->isAgencyOn());
    }

    public function testGetDefaultFolder()
    {
        $o = new SiteConfig();

        $this->assertEquals(0, $o->getDefaultFolder('foo'));
    }
}
