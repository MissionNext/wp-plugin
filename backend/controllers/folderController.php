<?php


namespace MissionNext\backend\controllers;

use MissionNext\Api;
use MissionNext\lib\Constants;
use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class folderController extends Controller {

    /**
     * @var Api
     */
    private $api;

    public function beforeAction(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    public function add(){

        if( !isset($_POST['role']) || !isset($_POST['folder'])){
            $this->forward404();
        }

        $response = $this->api->addFolder($_POST['role'], $_POST['folder']);

        echo json_encode($response?$response:0);

        return false;
    }

    public function delete(){

        if( !isset($_POST['id']) ){
            $this->forward404();
        }

        $response = $this->api->deleteFolder($_POST['id']);

        echo json_encode($response?$response:0);

        return false;
    }

    public function update(){

        if( !isset($_POST['id']) || !isset($_POST['folder'])){
            $this->forward404();
        }

        $response = $this->api->updateFolder($_POST['id'], $_POST['folder']);

        echo json_encode($response?$response:0);

        return false;
    }

    public function loadTranslation(){

        if( !isset($_POST['id']) || !isset($_POST['role']) ){
            $this->forward404();
        }

        $response = $this->api->getFoldersTranslation($_POST['role']);

        $translations = array();

        foreach($response as $folder){
            if($_POST['id'] == $folder['folder_id']){
                $translations[] = array(
                    'id' => $folder['lang_id'],
                    'value' => $folder['value']
                );
            }
        }

        echo json_encode($translations);

        return false;
    }

    public function saveTranslation(){

        if( !isset($_POST['id']) || !isset($_POST['translations']) ){
            $this->forward404();
        }

        $data = array();

        foreach($_POST['translations'] as $translation){
            $data[] = array(
                'folder_id' => $_POST['id'],
                'lang_id' => $translation['name'],
                'value' => $translation['value']
            );
        }

        $response = $this->api->saveFolderTranslation($data);

        echo json_encode($response?$response:0);

        return false;
    }

    public function makeDefault(){

        if(!isset($_POST['id']) || !isset($_POST['role'])){
            $this->forward404();
        }

        $response = Context::getInstance()->getSiteConfigManager()->save($_POST['role'] . "_default_folder", $_POST['id']);

        echo json_encode($response);

        return false;
    }

} 