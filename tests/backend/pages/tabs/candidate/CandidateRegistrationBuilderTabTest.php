<?php

use MissionNext\backend\pages\tabs\candidate\CandidateRegistrationBuilderTab;

class CandidateRegistrationBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new CandidateRegistrationBuilderTab('foo');

        $this->assertEquals('registration', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new CandidateRegistrationBuilderTab('foo');

        $this->assertEquals('candidate', $o->getRole());
    }
}
