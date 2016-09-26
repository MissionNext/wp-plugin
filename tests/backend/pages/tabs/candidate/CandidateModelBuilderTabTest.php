<?php

use MissionNext\backend\pages\tabs\candidate\CandidateModelBuilderTab;

class CandidateModelBuilderTabTest extends PHPUnit_Framework_TestCase
{
    public function testGetModelName()
    {
        $o = new CandidateModelBuilderTab('foo');

        $this->assertEquals('candidate', $o->getModelName());
    }
}
