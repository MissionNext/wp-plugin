<?php


namespace MissionNext\backend\pages\tabs\config;


use MissionNext\Api;
use MissionNext\backend\pages\tabs\AbstractSettingsTab;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

class LanguagesConfigTab extends AbstractSettingsTab {

    /**
     * @var Api
     */
    private $api;
    private $languages;
    private $default;
    private $default_language;

    public function initTab(){

        $this->api = Context::getInstance()->getApiManager()->getApi();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $post_langs = array('0'); // set default EN language

            if (isset($_POST['languages']) && is_array($_POST['languages'])){
                unset($post_langs[0]);
                $post_langs = array_merge($post_langs, $_POST['languages']);// combine with posted languages
            }

            if(isset($_POST['default_language']) && is_array($post_langs)){
                $this->saveDefaultLanguage($_POST['default_language']);
                $this->saveLanguages($post_langs);
                wp_redirect($_SERVER['REQUEST_URI']);
            } else {
                $this->addError('error', 'You have to choose at least one language!');
            }
        }

        $this->languages = $this->getLanguages();
        $this->default = $this->getDefaults();
        $this->default_language = Context::getInstance()->getSiteConfigManager()->get(Constants::CONFIG_DEFAULT_LANG, 0);

    }

    public function printContent(){
        renderTemplate('languages_config', array(
            'languages' => $this->languages,
            'default' => $this->default,
            'default_language' => $this->default_language
        ));
    }

    private function getLanguages(){

        $_langs = $this->api->getAllLanguages();

        $langs = array();

        foreach($_langs as $lang){
            $langs[$lang['id']] = $lang['name'];
        }

        return $langs;
    }

    private function getDefaults(){

        $_langs = $this->api->getSiteLanguages();
        $langs = array();

        foreach($_langs as $lang){
            $langs[] = $lang['id'];
        }

        return $langs ;
    }

    private function saveDefaultLanguage($lang){
        Context::getInstance()->getSiteConfigManager()->save(Constants::CONFIG_DEFAULT_LANG, $lang);
    }

    private function saveLanguages($langs){
        return $this->api->saveSiteLanguage($langs);
    }

} 