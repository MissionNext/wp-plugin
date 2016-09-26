<?php

use MissionNext\backend\pages\tabs\candidate\CandidateJobMatchingTab;

class CandidateJobMatchingTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetMainRole()
    {
        $o = new CandidateJobMatchingTab('foo');

        $this->assertEquals('candidate', $o->getMainRole());
    }

    public function testGetSecondaryRole()
    {
        $o = new CandidateJobMatchingTab('foo');

        $this->assertEquals('job', $o->getSecondaryRole());
    }
}
