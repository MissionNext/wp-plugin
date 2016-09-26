<?php

use MissionNext\lib\core\Routing;

class RoutingTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new Routing('foo');

        $this->assertTrue(method_exists($o, 'register'));
        $this->assertTrue(method_exists($o, 'getInstance'));
        $this->assertTrue(method_exists($o, '__construct'));
        $this->assertTrue(method_exists($o, 'getConfig'));
        $this->assertTrue(method_exists($o, 'check'));
        $this->assertTrue(method_exists($o, 'execute'));
        $this->assertTrue(method_exists($o, 'matchRoute'));
        $this->assertTrue(method_exists($o, 'renderTemplate'));
        $this->assertTrue(method_exists($o, 'getTemplatePath'));
    }
}
