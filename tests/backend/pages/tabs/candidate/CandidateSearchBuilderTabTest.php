<?php

use MissionNext\backend\pages\tabs\candidate\CandidateSearchBuilderTab;

class CandidateSearchBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new CandidateSearchBuilderTab('foo');

        $this->assertEquals('search', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new CandidateSearchBuilderTab('foo');

        $this->assertEquals('candidate', $o->getRole());
    }
}
