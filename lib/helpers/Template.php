<?php

function renderTemplate($name, $vars = array()){
    \MissionNext\lib\core\Context::getInstance()->getTemplateService()->render($name, $vars);
}

function getCustomTranslation($key, $default = ''){
    return \MissionNext\lib\core\Context::getInstance()->getCustomTranslationsManager()->get($key, $default);
}

function isAgencyOn(){
    return \MissionNext\lib\SiteConfig::isAgencyOn();
}

function getResourceUrl($path){
    return MN_PLUGIN_URL . $path;
}