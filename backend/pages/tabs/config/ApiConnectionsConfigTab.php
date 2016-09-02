<?php


namespace MissionNext\backend\pages\tabs\config;

use MissionNext\Api;
use MissionNext\backend\pages\tabs\AbstractSettingsTab;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\SiteConfigManager;

class ApiConnectionsConfigTab extends AbstractSettingsTab {

    /**
     * @var Api
     */
    protected $api;
    /**
     * @var SiteConfigManager
     */
    protected $site_config;
    protected $options;

    public function initTab(){

        Context::getInstance()->getApiManager()->testConnection(true);
        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->site_config = Context::getInstance()->getSiteConfigManager();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->save($_POST['config']);
        }

        // Set class property
        $this->options[Constants::PUBLIC_KEY_TOKEN] = get_option( Constants::PUBLIC_KEY_TOKEN );
        $this->options[Constants::PRIVATE_KEY_TOKEN] = get_option( Constants::PRIVATE_KEY_TOKEN );
        $this->options[Constants::CONFIG_AGENCY_TRIGGER] = $this->site_config->get(Constants::CONFIG_AGENCY_TRIGGER, true);
        $this->options[Constants::CONFIG_BLOCK_WEBSITE] = $this->site_config->get(Constants::CONFIG_BLOCK_WEBSITE, false);
    }

    public function save($data){

        if($data['public_key']){
            update_option(Constants::PUBLIC_KEY_TOKEN, $data['public_key']);
        }

        if($data['private_key']){
            update_option(Constants::PRIVATE_KEY_TOKEN, $data['private_key']);
        }

        if($this->api){
            $this->site_config->save(Constants::CONFIG_AGENCY_TRIGGER, intval(isset($data['agency_trigger'])));
            $this->site_config->save(Constants::CONFIG_BLOCK_WEBSITE, intval(isset($data['block_website'])));
        }

        wp_redirect($_SERVER['REQUEST_URI']);
        exit;
    }

    public function printContent(){

        renderTemplate("config/main", array(
            'options' => $this->options,
            'connected' => Context::getInstance()->getApiManager()->isConnected()
        ));
    }

} 