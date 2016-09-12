<?php

use MissionNext\lib\core\ConfigManager;

class ConfigManagerTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new ConfigManager();

        $this->assertTrue(method_exists($o, '__construct'));
        $this->assertTrue(method_exists($o, 'load'));
        $this->assertTrue(method_exists($o, 'save'));
        $this->assertTrue(method_exists($o, 'get'));
    }

    public function testLoadMethod()
    {
        $o = new ConfigManager();

        $this->assertFalse($o->load('foo'));
        $this->assertFalse($o->load('foo', 'bar'));
//        $this->assertJson($o->load('routing', NULL, FALSE));
    }
//
//    public function testSaveMethod()
//    {
//        $o = new ConfigManager();
////        $o->save();
//    }
//
    public function testGetMethod()
    {
        $o = new ConfigManager();
        $o->get('foo');

        $this->assertFalse($o->get('foo'));
        $this->assertFalse($o->get('foo', 'bar'));
//        $this->assertJson($o->get('routing', NULL, FALSE));
    }
}
