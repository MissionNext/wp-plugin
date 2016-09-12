<?php

use MissionNext\lib\core\TemplateService;

class TemplateServiceTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new TemplateService();

        $this->assertTrue(method_exists($o, '__construct'));
        $this->assertTrue(method_exists($o, 'render'));
    }
}
