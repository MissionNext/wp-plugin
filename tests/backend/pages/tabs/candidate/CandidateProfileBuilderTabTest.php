<?php

use MissionNext\backend\pages\tabs\candidate\CandidateProfileBuilderTab;

class CandidateProfileBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new CandidateProfileBuilderTab('foo');

        $this->assertEquals('profile', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new CandidateProfileBuilderTab('foo');

        $this->assertEquals('candidate', $o->getRole());
    }
}
