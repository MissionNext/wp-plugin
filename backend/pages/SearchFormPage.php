<?php


namespace MissionNext\backend\pages;


use MissionNext\backend\pages\tabs\agency\AgencySearchBuilderTab;
use MissionNext\backend\pages\tabs\candidate\CandidateSearchBuilderTab;
use MissionNext\backend\pages\tabs\job\JobSearchBuilderTab;
use MissionNext\backend\pages\tabs\organization\OrganizationSearchBuilderTab;
use MissionNext\lib\SiteConfig;

class SearchFormPage extends AbstractTabSettingsPage {

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
        parent::__construct('Search Form', 'Search Form', 'administrator');
    }

    public function pageInit(){

        $tabs = array(
            'candidate' => new CandidateSearchBuilderTab('Candidate Form'),
            'organization' => new OrganizationSearchBuilderTab('Receiving Organization Form'),
            'job' => new JobSearchBuilderTab('Job Form'),
        );

        if(SiteConfig::isAgencyOn()){
            $tabs['agency'] = new AgencySearchBuilderTab('Service Organization Form');
        }

        $this->setTabs($tabs);

        parent::pageInit();
    }



}