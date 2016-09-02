<?php


namespace MissionNext\lib\core;


abstract class Controller {

    public $messages = array();
    public $before_filters = array();
    public $after_filters = array();
    const FLASH_NAMESPACE = "messages";
    /**
     * @var FlashDataManager
     */
    private $flashManager;

    public function __construct(){
        $this->flashManager = Context::getInstance()->getFlashDataManager();
        $this->messages = $this->flashManager->getNamespace(self::FLASH_NAMESPACE);
    }

    public function beforeAction(){}

    public function afterAction(){}

    public function redirect($location, $status = 301){
        wp_redirect($location, $status);
        exit;
    }

    public function setMessage($key, $message, $jumps = 1){
        $this->messages[$key] = $message;

        if($jumps > 0){
            $this->flashManager->set($key, $message, $jumps, self::FLASH_NAMESPACE);
        }
    }

    public function forward($code){
        status_header($code);
        nocache_headers();
        exit;
    }

    public function forward404(){
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        exit;
    }

} 