<?php

use MissionNext\backend\pages\tabs\agency\AgencyFoldersTab;

class AgencyFoldersTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetRole()
    {
        $o = new AgencyFoldersTab('foo');

        $this->assertEquals('agency', $o->getRole());
    }
}
