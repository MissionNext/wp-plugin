<?php


namespace MissionNext\lib;

use MissionNext\lib\core\Context;

class PluginActivationManager {

    public function __construct(){
        register_activation_hook(MN_PLUGIN_FILE, array($this,'Activation'));
        register_deactivation_hook(MN_PLUGIN_FILE, array($this,'Deactivation'));
    }

    public function Activation(){
        add_option(Constants::PUBLIC_KEY_TOKEN);
        add_option(Constants::PRIVATE_KEY_TOKEN);

        Context::getInstance()->getDefaultDataManager()->createDefaults();
    }

    public function Deactivation(){
        delete_option(Constants::PUBLIC_KEY_TOKEN);
        delete_option(Constants::PRIVATE_KEY_TOKEN);

        Context::getInstance()->getDefaultDataManager()->removeDefaults();
    }
} 