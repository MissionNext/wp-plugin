<?php


namespace MissionNext\backend\pages\tabs;


use MissionNext\Api;
use MissionNext\lib\core\Context;

abstract class AbstractMatchingConfigTab extends AbstractSettingsTab {

    abstract function getMainRole();
    abstract function getSecondaryRole();

    private $mainFields;
    private $secondaryFields;
    private $defaults;
    /**
     * @var Api
     */
    private $api;

    public function initTab(){

        $this->api = Context::getInstance()->getApiManager()->getApi();

        $this->mainFields = $this->getFields($this->getMainRole());
        $this->secondaryFields = $this->getFields($this->getSecondaryRole());

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ret = $this->api->saveMatchingConfig($this->getSecondaryRole(), isset($_POST['mn_rels'])?array_values($_POST['mn_rels']):array());

            if($ret){
                $this->addNotice('config', 'Matching config saved');
            } else {
                $error = $this->api->getLastError();

                $this->addError(
                    $error['type'],
                    $error['message'] ? $error['message'] : $error['type']
                );
            }

        }

        $this->defaults = $this->getDefault();
    }

    public function printContent(){
        Context::getInstance()->getTemplateService()->render('_matching_config', array(
            'mainFields' => $this->mainFields,
            'secondaryFields' => $this->secondaryFields,
            'defaults' => $this->defaults,
            'mainRole' => $this->getMainRole(),
            'secondaryRole' => $this->getSecondaryRole()
        ));
    }

    private function getFields($role){

        $fields = $this->api->getModelFields($role);

        $fields = array_filter($fields, array($this, 'filterFields'));

        return $fields;
    }

    private function getDefault(){

        $defaults = $this->api->getMatchingConfig($this->getSecondaryRole());

        return $defaults;
    }

    private function filterFields($var){

        return in_array($var['type'], array('select', 'boolean', 'radio', 'select_multiple', 'checkbox', 'custom_marital', 'radio_yes_no'));

    }

} 