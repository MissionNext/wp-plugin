<?php

namespace MissionNext\backend\pages;

use MissionNext\backend\pages\tabs\agency\AgencyRegistrationBuilderTab;
use MissionNext\backend\pages\tabs\candidate\CandidateRegistrationBuilderTab;
use MissionNext\backend\pages\tabs\organization\OrganizationRegistrationBuilderTab;
use MissionNext\lib\SiteConfig;

class RegistrationBuilderPage extends AbstractTabSettingsPage {

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
        parent::__construct('Registration Form', 'Registration Form', 'administrator');
    }

    public function pageInit(){

        $tabs = array(
            'candidate' => new CandidateRegistrationBuilderTab('Candidates Registration'),
            'organization' => new OrganizationRegistrationBuilderTab('Receiving Organization Registration')
        );

        if(SiteConfig::isAgencyOn()){
            $tabs['agency'] = new AgencyRegistrationBuilderTab('Service Organization Registration');
        }

        $this->setTabs($tabs);

        parent::pageInit();
    }



} 