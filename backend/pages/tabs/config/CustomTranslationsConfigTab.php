<?php


namespace MissionNext\backend\pages\tabs\config;


use MissionNext\Api;
use MissionNext\backend\pages\tabs\AbstractSettingsTab;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\SiteConfig;

class CustomTranslationsConfigTab extends AbstractSettingsTab {

    /**
     * @var Api
     */
    private $api;
    private $languages;
    private $defaults;

    public function initTab(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->languages = $this->api->getSiteLanguages();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->submit($_POST['translations']);
        }

        $this->defaults = $this->getDefaults();
    }

    public function submit($_data){

        $data = array();

        foreach($_data as $key => $languages){
            foreach($languages as $lang_id => $value){
                $data[] = compact('lang_id', 'key', 'value');
            }
        }

        $response = $this->api->saveCustomTranslations($data);

        if($response){
            $this->addNotice('form', 'Custom translations successfully saved.');
        } else {
            $this->addError('form', 'Custom translations not saved.');
        }
    }

    public function getDefaults(){

        $defaults = $this->api->getCustomTranslations();

        $translations = array();

        foreach($defaults as $default){

            if(!isset($translations[$default['key']])){
                $translations[$default['key']] = array();
            }

            $translations[$default['key']][$default['lang_id']?$default['lang_id']:0] = $default['value'];
        }

        return $translations;
    }

    public function printContent(){
        renderTemplate("custom_translations", array(
            'languages' => $this->languages,
            'keys' => SiteConfig::isAgencyOn()?array_merge(Constants::$custom_translates, Constants::$agency_custom_translates):Constants::$custom_translates,
            'defaults' => $this->defaults
        ));
    }

}