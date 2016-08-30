<?php

use MissionNext\lib\UserConfigManager;

class UserConfigManagerTest extends PHPUnit_Framework_TestCase
{
    public function testLoad()
    {

    }

    public function testGet()
    {
        $o = new UserConfigManager();

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }

    public function testSet()
    {
        $o = new UserConfigManager();
        $o->set('foo', 'bar');

        $this->assertEquals('bar', $o->get('foo'));
    }

    public function testSave()
    {

    }
}
