<?php


namespace MissionNext\lib;


use MissionNext\Api;
use MissionNext\lib\core\Context;

class LocalizationManager {

    private $siteLanguages;
    private $siteDefaultLanguageId;
    /**
     * @var Api
     */
    private $api;

    public function __construct(){
        $this->init();

        add_action('wp', array($this, 'init'));
    }

    public function init(){

        load_plugin_textdomain( Constants::TEXT_DOMAIN, false, 'MissionNext/data/languages' );

        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->siteDefaultLanguageId = Context::getInstance()->getSiteConfigManager()->get(Constants::CONFIG_DEFAULT_LANG);
        if($this->api){
            $this->siteLanguages = $this->requestSiteLanguages();
            Context::getInstance()->getApiManager()->getApi()->setLang(!is_admin()?$this->getCurrentLangId():0);
        }
    }

    public function getLocalizedEmail($name){

        $dir = MN_ROOT_DIR . '/' . Constants::EMAILS_DIR;
        $publicKey = Context::getInstance()->getApiManager()->publicKey;
        $dir .= '/'.$publicKey;

        $locale = $this->getSiteDefaultLang();

        $locale_file = $dir . '/' . $locale . '/' . $name;
        $default_file = $dir . '/default/' . $name;

        return file_get_contents(is_file($locale_file)?$locale_file:$default_file);
    }

    public function requestSiteLanguages(){

        $_langs = Context::getInstance()->getApiManager()->getApi()->getSiteLanguages();

        $langs = array();

        if(!$_langs){
            return $langs;
        }

        foreach($_langs as $lang){
            $langs[$lang['id']] = $lang;
        }

        return $langs;
    }

    public function getSiteLanguages(){
        return $this->siteLanguages;
    }

    public function getSiteDefaultLang(){

        $langs = $this->getSiteLanguages();
        $default_lang_id = $this->getSiteDefaultLangId();

        return isset($langs[$default_lang_id])?$langs[$default_lang_id]['key']:'en';

    }

    public function getSiteDefaultLangId(){
        return $this->siteDefaultLanguageId;
    }

    public function getCurrentLocale(){

        if(function_exists('pll_current_language')){
            $lang = pll_current_language();

            return $lang?$lang:pll_default_language();
        }

        return get_locale();
    }

    public function getCurrentLang(){
        return substr($this->getCurrentLocale(), 0, 2);
    }

    public function getCurrentLangId(){

        $lang = $this->getCurrentLang();

        foreach($this->siteLanguages as $language){
            if($language['key'] == $lang){
                return $language['id'];
            }
        }

        //Hardcode english lang determination
        if($lang == 'en'){
            return 0;
        }

        return $this->getSiteDefaultLangId();
    }

} 