<?php

use MissionNext\backend\pages\tabs\organization\OrganizationFoldersTab;

class OrganizationFoldersTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetRole()
    {
        $o = new OrganizationFoldersTab('foo');

        $this->assertEquals('organization', $o->getRole());
    }
}
