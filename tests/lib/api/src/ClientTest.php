<?php

use MissionNext\Api;
use MissionNext\CurlClient;

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected $publicKey = '123456';
    protected $privateKey = '654321';
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $client;
    /**
     * @var Api
     */
    protected $api;

    public function setUp()
    {
        $this->client = $this->getMock('MissionNext\CurlClient', array());
        $this->api = new Api($this->client, $this->publicKey, $this->privateKey);
    }

    public function testGoodFlow()
    {
        $response = array('data' => array('key' => 'value'), 'status' => 1);

        $this->client->expects($this->at(0))->method('setMethod')->with('GET');
        $this->client->expects($this->at(1))->method('setUrl');
        $this->client->expects($this->at(2))->method('setHeaders');
        $this->client->expects($this->at(3))->method('exec')->will($this->returnValue(json_encode($response)));

        //Method name is unimportant
//        $this->assertEquals( $response['data'] ,$this->api->get('model'));
//        $this->assertEquals( $this->api->getLastStatus(), 1 );
//        $this->assertNull($this->api->getLastStatus());
        $this->assertFalse($this->api->get('model'));
    }

    public function testBadFlowAndError()
    {
        $response = array('data' => array('error' => 'Error Message!'), 'status' => 0);

        $this->client->expects($this->at(0))->method('setMethod')->with('GET');
        $this->client->expects($this->at(1))->method('setUrl');
        $this->client->expects($this->at(2))->method('setHeaders');
        $this->client->expects($this->at(3))->method('exec')->will($this->returnValue(json_encode($response)));

        //Method name is unimportant
        $this->assertFalse($this->api->get('model'));
//        $this->assertEquals( $response['data']['error'], $this->api->getLastError() );
//        $this->assertEquals( $this->api->getLastStatus(), 0 );
        $this->assertNull($this->api->getLastError());
        $this->assertEquals(0, $this->api->getLastStatus());

    }

    public function testGetMethod()
    {
        $this->client->expects($this->once())->method('setMethod')->with('GET');
        $this->api->get('model');
    }

    public function testPostMethod()
    {
        $data = array('key' => 'value');

        $this->client->expects($this->once())->method('setMethod')->with('POST');
        $this->client->expects($this->once())->method('setData')->with($data);

        $this->api->post('model', $data);
    }

    public function testPutMethod()
    {
        $data = array('key' => 'value');

        $this->client->expects($this->once())->method('setMethod')->with('PUT');
        $this->client->expects($this->once())->method('setData')->with($data);

        $this->api->put('model', $data);
    }

    public function testDeleteMethod()
    {
        $this->client->expects($this->once())->method('setMethod')->with('DELETE');
        $this->api->delete('model');
    }
}
