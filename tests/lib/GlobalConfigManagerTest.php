<?php

use MissionNext\lib\GlobalConfigManager;

class GlobalConfigManagerTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {

    }

    public function testGet()
    {
        $o = new GlobalConfigManager();

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }

    public function testSet()
    {
        $o = new GlobalConfigManager();
        $o->set('foo', 'bar');

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }
}
