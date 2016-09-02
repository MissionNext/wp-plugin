<?php

use MissionNext\lib\core\Context;

class ContextTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new Context();

        $this->assertTrue(method_exists($o, 'getInstance'));
        $this->assertTrue(method_exists($o, 'create'));
        $this->assertTrue(method_exists($o, 'init'));
        $this->assertTrue(method_exists($o, 'initialize'));
        $this->assertTrue(method_exists($o, 'getType'));
        $this->assertTrue(method_exists($o, 'get'));
        $this->assertTrue(method_exists($o, 'has'));
        $this->assertTrue(method_exists($o, 'set'));
        $this->assertTrue(method_exists($o, 'populateContext'));
        $this->assertTrue(method_exists($o, 'populateDefault'));
        $this->assertTrue(method_exists($o, '__call'));
    }
}
