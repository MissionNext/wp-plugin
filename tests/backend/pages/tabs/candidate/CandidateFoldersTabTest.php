<?php

use MissionNext\backend\pages\tabs\candidate\CandidateFoldersTab;

class CandidateFoldersTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetRole()
    {
        $o = new CandidateFoldersTab('foo');

        $this->assertEquals('candidate', $o->getRole());
    }
}
