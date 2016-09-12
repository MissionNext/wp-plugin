<?php

use MissionNext\backend\pages\tabs\organization\OrganizationRegistrationBuilderTab;

class OrganizationRegistrationBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new OrganizationRegistrationBuilderTab('foo');

        $this->assertEquals('registration', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new OrganizationRegistrationBuilderTab('foo');

        $this->assertEquals('organization', $o->getRole());
    }
}
