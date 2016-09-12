<?php

class ArrayTest extends PHPUnit_Framework_TestCase
{
    public function testElement()
    {
        $this->assertEquals('A', element('a', array('a' => 'A')));
        $this->assertEquals('C', element('b', array('a' => 'A'), 'C'));
    }
}
