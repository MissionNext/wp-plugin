<?php

use MissionNext\backend\pages\tabs\agency\AgencyRegistrationBuilderTab;

class AgencyRegistrationBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new AgencyRegistrationBuilderTab('foo');

        $this->assertEquals('registration', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new AgencyRegistrationBuilderTab('foo');

        $this->assertEquals('agency', $o->getRole());
    }
}
