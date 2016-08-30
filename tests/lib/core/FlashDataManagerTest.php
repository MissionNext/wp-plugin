<?php

use MissionNext\lib\core\FlashDataManager;

class FlashDataManagerTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new FlashDataManager();

        $this->assertTrue(method_exists($o, '__construct'));
        $this->assertTrue(method_exists($o, 'save'));
        $this->assertTrue(method_exists($o, 'set'));
        $this->assertTrue(method_exists($o, 'get'));
        $this->assertTrue(method_exists($o, 'has'));
        $this->assertTrue(method_exists($o, 'getNamespace'));
        $this->assertTrue(method_exists($o, 'hasNamespace'));
        $this->assertTrue(method_exists($o, 'load'));
        $this->assertTrue(method_exists($o, 'getKey'));
        $this->assertTrue(method_exists($o, 'clearOld'));
    }
}
