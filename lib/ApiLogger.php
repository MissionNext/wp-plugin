<?php


namespace MissionNext\lib;

use MissionNext\ClientInterface;
use MissionNext\lib\core\Context;

class ApiLogger extends CacheApi {

    const LOG = "Api";

    private $logger;

    public function __construct(ClientInterface $client, $publicKey, $privateKey, $basePath = 'http://api.missionfinder.net'){
        parent::__construct($client, $publicKey, $privateKey, $basePath);
        $this->logger = Context::getInstance()->getLogger();
    }

    protected function performRequest($url){

        $time = microtime(true);

        $response = parent::performRequest($url);

        $this->logger->log(self::LOG, "$url called and took " . (microtime(true) - $time) . "s");

        return $response;
    }

} 
