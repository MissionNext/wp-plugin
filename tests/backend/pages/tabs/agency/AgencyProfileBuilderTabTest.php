<?php

use MissionNext\backend\pages\tabs\agency\AgencyProfileBuilderTab;

class AgencyProfileBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetFormName()
    {
        $o = new AgencyProfileBuilderTab('foo');

        $this->assertEquals('profile', $o->getFormName());
    }

    public function testGetRole()
    {
        $o = new AgencyProfileBuilderTab('foo');

        $this->assertEquals('agency', $o->getRole());
    }
}
