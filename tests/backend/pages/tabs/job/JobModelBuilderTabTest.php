<?php

use MissionNext\backend\pages\tabs\job\JobModelBuilderTab;

class JobModelBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetModelName()
    {
        $o = new JobModelBuilderTab('foo');

        $this->assertEquals('job', $o->getModelName());
    }
}
