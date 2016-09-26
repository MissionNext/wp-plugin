<?php


namespace MissionNext\backend\pages\tabs\candidate;


use MissionNext\backend\pages\tabs\AbstractMatchingConfigTab;

class CandidateOrganizationMatchingTab extends AbstractMatchingConfigTab {

    public function getMainRole(){
        return 'candidate';
    }

    public function getSecondaryRole(){
        return 'organization';
    }

} 