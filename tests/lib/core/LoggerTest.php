<?php

use MissionNext\lib\core\Logger;

class LoggerTest extends PHPUnit_Framework_TestCase
{
    public function test_classMethods()
    {
        $o = new Logger();

        $this->assertTrue(method_exists($o, 'log'));
        $this->assertTrue(method_exists($o, 'buildLine'));
    }
}
