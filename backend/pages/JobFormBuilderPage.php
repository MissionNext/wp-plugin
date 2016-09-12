<?php


namespace MissionNext\backend\pages;


use MissionNext\backend\pages\tabs\job\JobFormBuilderTab;

class JobFormBuilderPage extends AbstractTabSettingsPage {

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
        parent::__construct('Job Form', 'Job Form', 'administrator');
    }

    public function pageInit(){

        $this->setTabs(array(
            'form' => new JobFormBuilderTab('Job Form')
        ));

        parent::pageInit();
    }



} 