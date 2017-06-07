<?php

namespace MissionNext\frontend\controllers;

use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\UserLib;

class inquireController extends AbstractLayoutController {


    public function show(){

        $this->layout = 'sidebarLayout.php';
        $this->inquiries = array();

        switch ($this->userRole) {
            case "candidate" : {
                $favorites = $this->api->getFavorites($this->userId, 'job');

                if ($favorites) {
                    foreach ($favorites as $key => $value) {
                        $favoritedJobs[$value['id']] = $value['target_id'];
                    }
                }

                $tmpInquiries = $this->api->getInquiredJobs($this->userId);
                $this->inquiries = [];
                foreach ($tmpInquiries as $jobItem) {
                    if ($favoritedJobs && in_array($jobItem['id'], $favoritedJobs)) {
                        $jobItem['favorite'] = true;
                    }
                    $this->inquiries[] = $jobItem;
                }
                break;
            }
            case "organization" : {
                $this->inquiries = $this->sortJobs($this->api->getInquiredCandidatesForOrganization($this->userId));
                break;
            }
            case "agency" : {
                $this->inquiries = $this->sortJobs($this->api->getInquiredCandidatesForAgency($this->userId));
                break;
            }
        }

        return 'inquire/' . $this->userRole . '.php';
    }

    public function inquire(){

        if($this->userRole != 'candidate' || !isset($_POST['id'])){
            $this->forward404();
        }

        $response = $this->api->inquireJob($this->userId, $_POST['id']);

        $job = $this->api->getJobProfile($_POST['id']);
        $organization = $this->api->getUserProfile($job['organization_id']);
        $affiliates = $this->api->getAffiliates($organization['id'], 'any');

        //Email send
        $mail_service = Context::getInstance()->getMailService();
        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];

        $text_message = Context::getInstance()->getLocalizationManager()->getLocalizedEmail('inquire_request.txt');

        $mail_service->send($organization['email'], __("Job Inquiry", Constants::TEXT_DOMAIN), UserLib::replaceTokens($text_message, $this->user, $organization, $job), '', array(), false);

        foreach($affiliates as $affiliate){

            if($affiliate['status'] == 'approved'){
                $mail_service->send($affiliate['agency_profile']['email'], __("Job Inquiry", Constants::TEXT_DOMAIN), UserLib::replaceTokens($text_message, $this->user, $affiliate['agency_profile'], $job), '', array(), false);
            }

        }

        echo json_encode($response);

        return false;
    }

    public function cancel(){

        if( $this->userRole != 'candidate' || !isset($_POST['job_id'])){
            $this->forward404();
        }

        $response = $this->api->cancelInquire($this->userId, $_POST['job_id']);

        $job = $this->api->getJobProfile($_POST['job_id']);
        $organization = $this->api->getUserProfile($job['organization_id']);
        $affiliates = $this->api->getAffiliates($organization['id'], 'any');

        //Email send
        $mail_service = Context::getInstance()->getMailService();
        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];

        $text_message = Context::getInstance()->getLocalizationManager()->getLocalizedEmail('inquire_cancel.txt');

        $mail_service->send($organization['email'], sprintf(__("%s inquire", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $organization, $job), '', array(), false);

        foreach($affiliates as $affiliate){

            if($affiliate['status'] == 'approved'){
                $mail_service->send($affiliate['agency_profile']['email'], sprintf(__("%s inquire", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $affiliate['agency_profile'], $job), '', array(), false);
            }

        }

        echo json_encode($response);

        return false;
    }

    public function cancelByAgency(){

        if( $this->userRole != 'agency'
            || !isset($_POST['inquire_id'])
            || !isset($_POST['job_id'])
            || !isset($_POST['candidate_id'])){
            $this->forward404();
        }

        $response = $this->api->cancelInquireByAgency($this->userId, $_POST['inquire_id']);

        $job = $this->api->getJobProfile($_POST['job_id']);
        $candidate = $this->api->getUserProfile($_POST['candidate_id']);
        $organization = $this->api->getUserProfile($job['organization_id']);
        $affiliates = $this->api->getAffiliates($organization['id'], 'any');

        //Email send
        $mail_service = Context::getInstance()->getMailService();
        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];

        $text_message = Context::getInstance()->getLocalizationManager()->getLocalizedEmail('inquire_cancel.txt');

        $mail_service->send($organization['email'], sprintf(__("%s inquire cancel", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $organization, $job), '', array(), false);
        $mail_service->send($candidate['email'], sprintf(__("%s inquire cancel", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $candidate, $job), '', array(), false);

        foreach($affiliates as $affiliate){

            if($affiliate['status'] == 'approved' && $affiliate['agency_profile']['id'] != $this->user['id']){
                $mail_service->send($affiliate['agency_profile']['email'], sprintf(__("%s inquire cancel", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $affiliate['agency_profile'], $job), '', array(), false);
            }

        }

        echo json_encode($response);

        return false;
    }

    public function cancelByOrganization(){

        if( $this->userRole != 'organization'
            || !isset($_POST['inquire_id'])
            || !isset($_POST['job_id'])
            || !isset($_POST['candidate_id'])){
            $this->forward404();
        }

        $response = $this->api->cancelInquireByOrganization($this->userId, $_POST['inquire_id']);

        $job = $this->api->getJobProfile($_POST['job_id']);
        $candidate = $this->api->getUserProfile($_POST['candidate_id']);
        $organization = $this->api->getUserProfile($job['organization_id']);
        $affiliates = $this->api->getAffiliates($organization['id'], 'any');

        //Email send
        $mail_service = Context::getInstance()->getMailService();
        $mail_service->from = 'no-reply@'.$_SERVER['SERVER_NAME'];

        $text_message = Context::getInstance()->getLocalizationManager()->getLocalizedEmail('inquire_cancel.txt');

        $mail_service->send($candidate['email'], sprintf(__("%s inquire cancel", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $candidate, $job), '', array(), false);

        foreach($affiliates as $affiliate){

            if($affiliate['status'] == 'approved' && $affiliate['agency_profile']['id'] != $this->user['id']){
                $mail_service->send($affiliate['agency_profile']['email'], sprintf(__("%s inquire cancel", Constants::TEXT_DOMAIN), ucfirst(getCustomTranslation(Constants::ROLE_JOB))), UserLib::replaceTokens($text_message, $this->user, $affiliate['agency_profile'], $job), '', array(), false);
            }

        }

        echo json_encode($response);

        return false;
    }

    private function sortJobs($inquiries){

        $result = array();

        if($inquiries){
            $old_id = 0;
            foreach($inquiries as $inquirie){

                $job_id = $inquirie['job']['id'];

                if(!isset($result[$job_id])){
                    $result[$job_id] = $inquirie['job'];
                    $result[$job_id]['inquiries'] = array();
                }

                unset($inquirie['job']);

                $favs = $this->api->getFavorites($this->userId, 'candidate');

                $inquirie['favorite'] = null;
                foreach($favs as $fav){
                    if($inquirie['candidate']['id'] == $fav['target_id']){
                        $inquirie['favorite'] = $fav['id'];
                        break;
                    }
                }

                $result[$job_id]['inquiries'][] = $inquirie;

                if($old_id != $job_id){
                    $old_id = $job_id;

                    uasort($result[$job_id]['inquiries'], function($a, $b){
                        return strtotime($a['updated_at']) > strtotime($b['updated_at']) ? -1 : 1;
                    });
                }
            }
        }

        uasort($result, function($a, $b){
            return strcasecmp($a['name'], $b['name']);
        });

        return $result;

    }

} 