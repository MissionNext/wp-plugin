<?php


namespace MissionNext\backend\pages\tabs;


use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;

abstract class AbstractFolderTab extends AbstractSettingsTab {

    protected $role;
    protected $folders;
    /**
     * @var Api
     */
    protected $api;

    public function initTab(){

        $this->api = Context::getInstance()->getApiManager()->getApi();
        $this->role = $this->getRole();
        $this->folders = $this->api->getFolders($this->role);

        uasort($this->folders, array($this, 'sortFolders'));
    }

    public function printContent(){
        Context::getInstance()->getTemplateService()->render('_folders', array(
            'folders' => $this->folders,
            'role' => $this->role,
            'languages' => $this->api->getSiteLanguages(),
            'default' => Context::getInstance()->getSiteConfigManager()->get($this->role . '_default_folder', 0)
        ));
    }

    abstract function getRole();

    private function sortFolders($a, $b){
        return $a['id'] < $b['id']? -1 : 1;
    }
} 