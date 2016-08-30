<?php

namespace MissionNext\backend\pages;

use MissionNext\backend\pages\tabs\agency\AgencyModelBuilderTab;
use MissionNext\backend\pages\tabs\candidate\CandidateModelBuilderTab;
use MissionNext\backend\pages\tabs\job\JobModelBuilderTab;
use MissionNext\backend\pages\tabs\organization\OrganizationModelBuilderTab;
use MissionNext\lib\core\Context;
use MissionNext\lib\SiteConfig;

class ModelBuilderPage extends AbstractTabSettingsPage {

    private static $INSTANCE;

    public static function register(){
        if( null == self::$INSTANCE ){
            self::$INSTANCE = new self();
        }
    }

    public function __construct()
    {
        parent::__construct('Model', 'Model', 'administrator', 'mission_next');

    }

    public function addPage(){

        add_menu_page(
            'Main options',
            'MissionNext',
            'administrator',
            'mission_next'
        );

        $tabs = array(
            'candidates' => new CandidateModelBuilderTab('Candidates Model'),
            'organization' => new OrganizationModelBuilderTab('Receiving Organization Model'),
            'job' => new JobModelBuilderTab('Job Model')
        );

        if(SiteConfig::isAgencyOn()){
            $tabs['agency'] = new AgencyModelBuilderTab('Service Organization Model');
        }

        $this->setTabs($tabs);

        parent::addPage();
    }

}