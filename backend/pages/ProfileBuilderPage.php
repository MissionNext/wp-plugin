<?php

namespace MissionNext\backend\pages;

use MissionNext\backend\pages\tabs\agency\AgencyProfileBuilderTab;
use MissionNext\backend\pages\tabs\candidate\CandidateProfileBuilderTab;
use MissionNext\backend\pages\tabs\organization\OrganizationProfileBuilderTab;
use MissionNext\lib\SiteConfig;

class ProfileBuilderPage extends AbstractTabSettingsPage {

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
        parent::__construct('Profile Form', 'Profile Form', 'administrator');
    }

    public function pageInit(){

        $tabs = array(
            'candidate' => new CandidateProfileBuilderTab('Candidates Profile'),
            'organization' => new OrganizationProfileBuilderTab('Receiving Organization Profile')
        );

        if(SiteConfig::isAgencyOn()){
            $tabs['agency'] = new AgencyProfileBuilderTab('Service Organization Profile');
        }

        $this->setTabs($tabs);

        parent::pageInit();
    }



} 