<?php


namespace MissionNext\lib;

use MissionNext\backend\pages\AdminConfigPage;
use MissionNext\backend\pages\FoldersPage;
use MissionNext\backend\pages\JobFormBuilderPage;
use MissionNext\backend\pages\MainAdminConfigPage;
use MissionNext\backend\pages\MatchingConfigPage;
use MissionNext\backend\pages\ModelBuilderPage;
use MissionNext\backend\pages\ProfileBuilderPage;
use MissionNext\backend\pages\RegistrationBuilderPage;
use MissionNext\backend\pages\SearchFormPage;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Routing;

class RoutineManager {

    public function __construct(){

        if(is_admin()){
            Routing::register('backend');
            $this->registerBackendPages();
        } else {
            Routing::register('frontend');
        }

    }

    //TODO rethink && refactor
    public function registerBackendPages(){

        if(Context::getInstance()->getApiManager()->isConnected()){
            ModelBuilderPage::register();
            MatchingConfigPage::register();
            RegistrationBuilderPage::register();
            ProfileBuilderPage::register();
            JobFormBuilderPage::register();
            SearchFormPage::register();
            FoldersPage::register();
            AdminConfigPage::register();
        } else {
            MainAdminConfigPage::register();
        }

    }

    /**
     * @return Routing
     */
    public function getRouting(){
        return Routing::getInstance();
    }

} 