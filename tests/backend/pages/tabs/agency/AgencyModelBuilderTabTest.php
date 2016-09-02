<?php

use MissionNext\backend\pages\tabs\agency\AgencyModelBuilderTab;

class AgencyModelBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetModelName()
    {
        $o = new AgencyModelBuilderTab('foo');

        $this->assertEquals('agency', $o->getModelName());
    }
}
