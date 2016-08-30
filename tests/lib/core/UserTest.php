<?php

use MissionNext\lib\core\User;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function test_classProperties()
    {
        $o = new User();

        $this->assertObjectHasAttribute('wp_user', $o);
        $this->assertObjectHasAttribute('user', $o);
        $this->assertObjectHasAttribute('cache_manager', $o);
    }

    public function test_classMethods()
    {
        $o = new User();

        $this->assertTrue(method_exists($o, '__construct'));
        $this->assertTrue(method_exists($o, 'getWPUser'));
        $this->assertTrue(method_exists($o, 'updateWPUser'));
        $this->assertTrue(method_exists($o, 'getUser'));
        $this->assertTrue(method_exists($o, 'getName'));
        $this->assertTrue(method_exists($o, 'getWPMeta'));
        $this->assertTrue(method_exists($o, 'initData'));
    }

    public function test_getWPUser()
    {
        $o = new User();

        $this->assertNotEmpty($o->getWPUser());
    }

    //...

    public function test_getUser()
    {
        $o = new User();

        $this->assertNull($o->getUser());
    }
}
