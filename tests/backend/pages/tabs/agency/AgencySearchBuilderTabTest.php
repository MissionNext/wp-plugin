<?php

use MissionNext\backend\pages\tabs\agency\AgencySearchBuilderTab;

class AgencySearchBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new AgencySearchBuilderTab('foo');

        $this->assertEquals('search', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new AgencySearchBuilderTab('foo');

        $this->assertEquals('agency', $o->getRole());
    }
}
