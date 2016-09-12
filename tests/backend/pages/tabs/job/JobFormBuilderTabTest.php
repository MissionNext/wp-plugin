<?php

use MissionNext\backend\pages\tabs\job\JobFormBuilderTab;

class JobFormBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new JobFormBuilderTab('foo');

        $this->assertEquals('job', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new JobFormBuilderTab('foo');

        $this->assertEquals('job', $o->getRole());
    }
}
