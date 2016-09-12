<?php


namespace MissionNext\backend\pages;


use MissionNext\backend\pages\tabs\agency\AgencyFoldersTab;
use MissionNext\backend\pages\tabs\candidate\CandidateFoldersTab;
use MissionNext\backend\pages\tabs\job\JobFoldersTab;
use MissionNext\backend\pages\tabs\organization\OrganizationFoldersTab;
use MissionNext\lib\SiteConfig;

class FoldersPage extends AbstractTabSettingsPage {

    private static $INSTANCE;

    public static function register(){
        if( null == self::$INSTANCE ){
            self::$INSTANCE = new self();
        }
    }

    public function __construct()
    {
        parent::__construct('Folders', 'Folders', 'administrator');

    }

    public function pageInit(){

        $tabs = array(
            'candidates' => new CandidateFoldersTab('Candidates Folders'),
            'organization' => new OrganizationFoldersTab('Receiving Organization Folders'),
            'job' => new JobFoldersTab('Job Folders')
        );

        if(SiteConfig::isAgencyOn()){
            $tabs['agency'] = new AgencyFoldersTab('Service Organization Folders');
        }

        $this->setTabs($tabs);

        parent::pageInit();
    }

}