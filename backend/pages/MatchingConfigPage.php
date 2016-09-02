<?php


namespace MissionNext\backend\pages;


use MissionNext\backend\pages\tabs\candidate\CandidateJobMatchingTab;
use MissionNext\backend\pages\tabs\candidate\CandidateOrganizationMatchingTab;

class MatchingConfigPage extends AbstractTabSettingsPage {

    private static $INSTANCE;

    public static function register(){
        if( null == self::$INSTANCE ){
            self::$INSTANCE = new self();
        }
    }

    public function __construct()
    {
        parent::__construct('Matching Rules', 'Matching Rules', 'administrator');

    }

    public function pageInit(){

        $this->setTabs(array(
            'candidate_organization' => new CandidateOrganizationMatchingTab('Candidate to Receiving Organization Matching config'),
            'candidate_job' => new CandidateJobMatchingTab('Candidate to Job Matching config')
        ));

        parent::pageInit();
    }

}