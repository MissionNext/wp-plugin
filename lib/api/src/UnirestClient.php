<?php

namespace MissionNext;

class UnirestClient implements ClientInterface{

    private $url;
    private $headers = array();
    private $data;
    private $method;

    private $supportedMethods = array( 'get', 'post', 'put', 'patch', 'delete' );

    public function __construct(){
        \Unirest::timeout(10);
    }

    public function setUrl ( $url )
    {
        $this->url = $url;
    }

    public function setMethod ( $method )
    {
        if(in_array(strtolower($method), $this->supportedMethods)){
            $this->method = $method;
        } else {
            throw new \InvalidArgumentException("Method $method is not supported.");
        }
    }

    public function setData ( $data )
    {
        $this->data = $data;
    }

    public function setHeader ( $key, $value )
    {
        $this->headers[$key] = $value;
    }

    public function exec ()
    {
        $method = strtolower($this->method);

        $response = \Unirest::$method($this->url, $this->headers, $this->data?$this->buildData($this->data):null);

        return $response->raw_body;
    }

    private function buildData($_data){

        $data = array();

        foreach($_data as $key => $value){

            if(is_array($value)){
                $data[$key] = $this->buildData($value);
            } else {
                if(strpos($value, '@') === 0){

                    $fpath = substr($value, 1);

                    $data[$key] = function_exists('curl_file_create')?curl_file_create($fpath, mime_content_type($fpath), $key):$value;
                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }
}
