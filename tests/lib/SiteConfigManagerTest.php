<?php

use MissionNext\lib\SiteConfigManager;

class SiteConfigManagerTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {

    }

    public function testGet()
    {
        $o = new SiteConfigManager();

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }

    public function testSet()
    {
        $o = new SiteConfigManager();
        $o->set('foo', 'bar');

        $this->assertEquals('bar', $o->get('foo'));
    }

    public function testSave()
    {

    }
}
