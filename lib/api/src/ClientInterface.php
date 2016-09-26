<?php

namespace MissionNext;


interface ClientInterface {

    public function setUrl($url);

    public function setMethod($method);

    public function setData($data);

    public function setHeader($key, $value);

    public function exec();

}