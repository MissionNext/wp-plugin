<?php


namespace MissionNext\lib\core;


class Logger {

    private $status;

    private $dir;
    private $file;

    private $fh;
    private $startTime = 0;
    private $requestRowNumber = 0;

    public function __construct(){
        $this->status = Context::getInstance()->getConfig()->get("logger", false);
        $this->dir = MN_ROOT_DIR . '/' . Context::getInstance()->getConfig()->get('logger_dir', 'data/logs');
        $this->file = 'logs_'.date('Y-m-d H').'.txt';

        if($this->status){
            $this->fh = fopen($this->dir . '/' . $this->file, 'a');
            $this->startTime = microtime(true);
            $this->log("Logger", 'Logger init ' . $_SERVER['REQUEST_METHOD'] . ' ' .$_SERVER['REQUEST_URI']);
        }
    }

    public function log($key, $text){

        if($this->status){
            fwrite($this->fh, $this->buildLine($key, $text) . PHP_EOL );
            $this->requestRowNumber++;
        }
    }

    private function buildLine($key, $text){

        if($this->requestRowNumber == 0){
            return sprintf("[%s] %s - %s", date("Y-m-d H:i:s", time()), $key, $text);
        } else {
            return sprintf("%d. %s - %s (%s)", $this->requestRowNumber, $key, $text, microtime(true) - $this->startTime);
        }

    }

    public function __destruct(){

        $this->log('Logger', "Request end, time: " . (microtime(true) - $this->startTime));

        if($this->fh){
            fclose($this->fh);
        }
    }
}