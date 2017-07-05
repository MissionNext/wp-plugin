<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 24.09.15
 * Time: 16:44
 */

namespace MissionNext\frontend\controllers;

use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class customAjaxController extends Controller
{
    private $api;

    public function __construct(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    /**
     * Функция выбора страны.
     */
    public function selectCountry($params)
    {
        if(is_post())
        {
            $name = element('name', $_POST);
            if(!empty($name))
            {
                $file = MN_ROOT_DIR . '/resources/json/countries.json';
                if(is_file($file))
                {
                    $countries = json_decode(file_get_contents($file), true);
                    if(!empty($countries))
                    {
                        $lang_id = Context::getInstance()->getLocalizationManager()->getCurrentLangId();

                        $states  = array();
                        $choices = array();

                        $languages = $this->api->getFieldsLanguages($params[0]);
                        if(!empty($languages))
                        {
                            foreach($languages as $language)
                            {
                                foreach($language['fields'] as $field)
                                {
                                    if($language['lang_id'] == 0)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $states = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }

                                    if($language['lang_id'] == $lang_id)
                                    {
                                        if($field['symbol_key'] == 'state')
                                        {
                                            $choices = array_combine($field['dictionary_id'], $field['choices']);
                                        }
                                    }
                                }
                            }
                        }
                        unset($languages);

                        foreach($countries as $country)
                        {
                            if($country['name'] == $name)
                            {
                                if(!empty($country['states']))
                                {
                                    foreach($country['states'] as $s => $state)
                                    {
                                        if(in_array($state['name'], $states))
                                        {
                                            $value = element(array_search($state['name'], $states), $choices);

                                            if(!empty($value))
                                            {
                                                $country['states'][$s]['name'] = $value;
                                            }

                                            $country['states'][$s]['value'] = $state['name'];
                                        }
                                    }

                                    array_unshift($country['states'], [ 'name' => '', 'abbr' => '', 'value' => '' ]);

                                    echo json_encode($country['states'], TRUE);
                                }
                            }
                        }
                    }
                    unset($countries);
                }
                unset($file);
            }
            unset($name);
        }

        return false;
    }

    public function deleteFile()
    {
        $result = '';
        if (is_post()) {
            $fieldname = element('fieldname', $_POST);
            $user_id = element('userid', $_POST);

            $result = $this->api->deleteProfileFile($fieldname, $user_id);
        }

        echo json_encode($result);

        return false;
    }

    public function deleteJobFile()
    {
        $result = '';
        if (is_post()) {
            $fieldname = element('fieldname', $_POST);
            $job_id = element('jobid', $_POST);

            $result = $this->api->deleteJobProfileFile($fieldname, $job_id);
        }

        echo json_encode($result);

        return false;
    }

    public function selectUserPrefers()
    {
        $role = element('role', $_GET);
        $userid = element('userid', $_GET);

        if($role == Constants::ROLE_CANDIDATE){
            $orgFavorites = $this->api->getFavorites($userid, 'organization');
            $jobFavorites = $this->api->getFavorites($userid, 'job');
            $favoritesCount = count($orgFavorites) + count($jobFavorites);

            $inquiriesCount = $this->getInquiriesViews($role, $userid);
            $result = [
                'favoritesCount'    => $favoritesCount,
                'inquiriesCount'   => $inquiriesCount
            ];
        } else {
            $favorites = $this->api->getFavorites($userid, 'candidate');
            $favoritesCount = count($favorites);

            $inquiriesCount = $this->getInquiriesViews($role, $userid);

            $affiliates = $this->api->getAffiliates($userid, 'any');
            $affiliatesCount = count($affiliates);

            $result = [
                'favoritesCount'    => $favoritesCount,
                'inquiriesCount'    => $inquiriesCount,
                'affiliatesCount'   => $affiliatesCount
            ];
        }

        echo json_encode($result);

        return false;
    }

    public function getUserSubscriptions() {
        $userid = element('userid', $_GET);

        $subscriptions = $this->api->getSubscriptionsForUser($userid);

        $configs = $this->api->getSubscriptionConfigs();
        $candidateSubscriptions = [];
        $blockedIndexes = [];
        foreach($configs as $app){
            $app_block = false;
            foreach($app['configs'] as $app_options){
                if ("block_website" == $app_options['key']){
                    $app_block = $app_options['value'];
                }
            }
            if (!$app_block) {
                foreach($app['sub_configs'] as $sub_config){
                    if($sub_config['role'] == Constants::ROLE_CANDIDATE && $sub_config['price_year'] == 0){
                        $candidateSubscriptions[$app['id']] = $sub_config;
                        $candidateSubscriptions[$app['id']]['app_name'] = $app['name'];
                    }
                }
            } else {
                $blockedIndexes[] = $app["id"];
            }
        }

        $subsIndexes = [];
        $counter = 0;
        foreach($subscriptions as $sub_item){
            unset($candidateSubscriptions[$sub_item['app_id']]);

            if(in_array($sub_item['app_id'], $blockedIndexes)){
                $subsIndexes[] = $counter;
            }
            $counter++;
        }

        foreach($subsIndexes as $val){
            unset($subscriptions[$val]);
        }

        $result = [
            'subscriptions' => $subscriptions,
            'candidateSubs' => $candidateSubscriptions
        ];

        echo json_encode($result);

        return false;
    }

    private function getInquiriesViews($role, $userid){
        $inquiries = array();

        switch ($role) {
            case "candidate" : {

                $tmpInquiries = $this->api->getInquiredJobs($userid);
                $inquiries = [];
                foreach ($tmpInquiries as $jobItem) {
                    $inquiries[] = $jobItem;
                }
                break;
            }
            case "organization" : {
                $inquiries = $this->api->getInquiredCandidatesForOrganization($userid);
                break;
            }
            case "agency" : {
                $inquiries = $this->api->getInquiredCandidatesForAgency($userid);
                break;
            }
        }

        return count($inquiries);
    }
}