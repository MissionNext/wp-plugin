<?php

use MissionNext\backend\pages\tabs\job\JobFoldersTab;

class JobFoldersTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetRole()
    {
        $o = new JobFoldersTab('foo');

        $this->assertEquals('job', $o->getRole());
    }
}
