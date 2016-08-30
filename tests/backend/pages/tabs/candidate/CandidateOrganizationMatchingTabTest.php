<?php

use MissionNext\backend\pages\tabs\candidate\CandidateOrganizationMatchingTab;

class CandidateOrganizationMatchingTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetMainRole()
    {
        $o = new CandidateOrganizationMatchingTab('foo');

        $this->assertEquals('candidate', $o->getMainRole());
    }

    public function testGetSecondaryRole()
    {
        $o = new CandidateOrganizationMatchingTab('foo');

        $this->assertEquals('organization', $o->getSecondaryRole());
    }
}
