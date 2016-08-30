<?php

use MissionNext\lib\MailService;

class MailServiceTest extends PHPUnit_Framework_TestCase
{
    public function testSend()
    {

    }

    public function testHookFromName()
    {
        $o = new MailService();

        $this->assertEquals('foo', $o->hookFromName('foo'));
    }

    public function testHookFrom()
    {
        $o = new MailService();

        $this->assertEquals('foo', $o->hookFrom('foo'));
    }

    public function testHookContentType()
    {
        $o = new MailService();

        $this->assertEquals('text/html', $o->hookContentType('foo'));
    }

    public function testHookCharset()
    {
        $o = new MailService();

        $this->assertEquals('foo', $o->hookCharset('foo'));
    }

    public function testReset()
    {
        $o = new MailService();
        $o->reset();

        $this->assertNull($o->from);
        $this->assertNull($o->fromName);
        $this->assertNull($o->contentType);
        $this->assertNull($o->charset);
    }
}
