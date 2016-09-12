<?php

use MissionNext\backend\pages\tabs\job\JobSearchBuilderTab;

class JobSearchBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new JobSearchBuilderTab('foo');

        $this->assertEquals('search', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new JobSearchBuilderTab('foo');

        $this->assertEquals('job', $o->getRole());
    }
}
