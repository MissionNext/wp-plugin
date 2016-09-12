<?php

use MissionNext\backend\pages\tabs\organization\OrganizationSearchBuilderTab;

class OrganizationSearchBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new OrganizationSearchBuilderTab('foo');

        $this->assertEquals('search', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new OrganizationSearchBuilderTab('foo');

        $this->assertEquals('organization', $o->getRole());
    }
}
