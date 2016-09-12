<?php

use MissionNext\backend\pages\tabs\organization\OrganizationProfileBuilderTab;

class OrganizationProfileBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new OrganizationProfileBuilderTab('foo');

        $this->assertEquals('profile', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new OrganizationProfileBuilderTab('foo');

        $this->assertEquals('organization', $o->getRole());
    }
}
