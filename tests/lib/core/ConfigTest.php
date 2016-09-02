<?php

use MissionNext\lib\core\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testHasMethod()
    {
        $o = new Config();

        $this->assertTrue($o->has('helpers'));
        $this->assertFalse($o->has('foo'));
    }

    public function testGetMethod()
    {
        $o = new Config();

        $this->assertEquals('bar', $o->get('foo', 'bar'));
    }

    public function testSetMethod()
    {
        $o = new Config();
        $o->set('foo', 'bar');

        $this->assertTrue($o->has('foo'));
    }
}
