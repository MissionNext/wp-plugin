<?php

namespace MissionNext\backend\pages;

use MissionNext\backend\pages\tabs\config\ApiConnectionsConfigTab;
use MissionNext\backend\pages\tabs\config\CustomTranslationsConfigTab;
use MissionNext\backend\pages\tabs\config\LanguagesConfigTab;
use MissionNext\lib\core\Context;

class AdminConfigPage extends AbstractTabSettingsPage {

    private static $INSTANCE;

    public static function register(){
        if( null == self::$INSTANCE ){
            self::$INSTANCE = new self();
        }
    }

    /**
    * Start up
    */
    public function __construct()
    {
        parent::__construct('Config', 'Config', 'administrator');
    }

    public function pageInit(){

        $tabs = array(
            'api_connection' => new ApiConnectionsConfigTab("Main config")
        );

        if(Context::getInstance()->getApiManager()->isConnected()){
            $tabs['languages'] = new LanguagesConfigTab("Languages Config");
            $tabs['custom_translations'] = new CustomTranslationsConfigTab("Custom translations");
        }

        $this->setTabs($tabs);

        parent::pageInit();
    }
}