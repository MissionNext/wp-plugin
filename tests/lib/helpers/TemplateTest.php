<?php

class TemplateTest extends PHPUnit_Framework_TestCase
{
    public function testRenderTemplate()
    {

    }

    public function testGetCustomTranslation()
    {
//        $this->assertEquals('bar', getCustomTranslation('foo', 'bar'));
    }

    public function testIsAgencyOn()
    {
        $this->assertTrue(isAgencyOn());
    }

    public function testGetResourceUrl()
    {
        $this->assertNotEmpty(getResourceUrl('foo'));
    }
}
