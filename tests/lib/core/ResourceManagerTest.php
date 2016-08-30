<?php

use MissionNext\lib\core\ResourceManager;

class ResourceManagerTest extends PHPUnit_Framework_TestCase
{
    public function testAddJSResource()
    {

    }

    public function testAddCSSResource()
    {

    }

    public function testGetUser()
    {
        $o = new ResourceManager();

        $this->assertNotEmpty($o->getImageUrl('foo'));
    }
}
