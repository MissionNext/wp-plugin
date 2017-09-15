<?php

namespace MissionNext;


class CurlClient implements ClientInterface {

    private $ch;
    private $url;
    private $method;
    private $headers = array();
    private $data;

    public function __construct(){
        $this->ch = curl_init();

        register_shutdown_function(array($this, 'shutdown'));
    }

    private function setDefaultOptions(){
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
    }

    public function setUrl ( $url )
    {
        $this->url = $url;
    }

    public function setMethod ( $method )
    {
        $this->method = strtolower($method);
    }

    public function setData ( $data )
    {

        $this->data = $data;
    }

    public function setHeader ( $key, $value )
    {
        $this->headers[$key] = $value;
    }

    public function exec()
    {
        $this->setDefaultOptions();

        curl_setopt($this->ch, CURLOPT_URL, $this->url);

        if($this->headers){
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->buildHeaders($this->headers) );
        }

        if($this->data){
            $data_has_files = $this->hasFiles($this->data);

            if($data_has_files && ($this->method == 'put' || $this->method == 'patch') ){
                curl_setopt($this->ch, CURLOPT_POST, 1);
                $this->data['_method'] = $this->method;

            } else {
                curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
            }

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data_has_files?$this->buildRequest($this->convertToForm($this->data)):http_build_query($this->data));
        } else {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->method));
        }

        $resp = curl_exec($this->ch);

        //debug code
        try {
            if (FALSE === $resp) {
                throw new \Exception(curl_error($this->ch), curl_errno($this->ch));
            }
        } catch(\Exception $e) {
            trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
        }


        curl_reset($this->ch);

        return $resp;
    }

    public function shutdown(){
        curl_close($this->ch);
    }

    private function hasFiles($data){
        foreach($data as $key => $value){
            if(is_array($value)){
                $file = $this->hasFiles($value);

                if($file){
                    return $file;
                }
            } else {
                if(strpos($value, '@') === 0){
                    return substr($value, 1);
                }
            }
        }
        return false;
    }

    private function buildHeaders($map){

        $array = array();

        foreach($map as $key => $value){
            $array[] = $key . ': ' . $value;
        }

        return $array;
    }

    private function buildRequest($_data){

        $data = array();

        foreach($_data as $key => $value){

            if(is_array($value)){
                $data[$key] = $this->buildRequest($value);
            } else {
                if(strpos($value, '@') === 0){

                    $fpath = substr($value, 1);

                    $data[$key] = (is_file($fpath) && function_exists('curl_file_create'))?curl_file_create($fpath, mime_content_type($fpath), basename($fpath)):$value;

                } else {
                    $data[$key] = $value;
                }
            }
        }

        return $data;
    }

    //TODO: If you now how to do it recursive, PLZ do it =)
    function convertToForm($data){

        $rdata = array();

        if(is_array($data)){

            foreach($data as $k => $v){
                if(is_array($v)){
                    foreach($v as $k1 => $v1){
                        if(is_array($v1)){
                            foreach($v1 as $k2 => $v2){
                                if (is_array($v2)) {
                                    foreach ($v2 as $k3 => $v3) {
                                        $rdata[$k."[$k1][$k2][$k3]"] = $v3;
                                    }
                                } else {
                                    $rdata[$k."[$k1][$k2]"] = $v2;
                                }
                            }
                        } else {
                            $rdata[$k."[$k1]"] = $v1;
                        }
                    }
                } else {
                    $rdata[$k] = $v;
                }
            }

        } else {
            return $data;
        }

        return $rdata;
    }
}