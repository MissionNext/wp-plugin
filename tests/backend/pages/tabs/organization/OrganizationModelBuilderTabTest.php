<?php

use MissionNext\backend\pages\tabs\organization\OrganizationModelBuilderTab;

class OrganizationModelBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetModelName()
    {
        $o = new OrganizationModelBuilderTab('foo');

        $this->assertEquals('organization', $o->getModelName());
    }
}
