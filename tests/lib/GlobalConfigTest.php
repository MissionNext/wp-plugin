<?php

use MissionNext\lib\GlobalConfig;

class GlobalConfigTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {

    }

    public function testGetSubscriptionDiscount()
    {
        $o = new GlobalConfig();

        $this->assertNotEmpty($o->getSubscriptionDiscount());
    }

    public function testGetSubscriptionFee()
    {
        $o = new GlobalConfig();

        $this->assertNotEmpty($o->getSubscriptionFee());
    }
}
