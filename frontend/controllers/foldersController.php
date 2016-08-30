<?php
/**
 * Created by PhpStorm.
 * User: wizard
 * Date: 20.10.15
 * Time: 16:56
 */

namespace MissionNext\frontend\controllers;


use MissionNext\lib\core\Context;
use MissionNext\lib\core\Controller;

class foldersController extends Controller
{
    private $api;
    public $layout = 'sidebarLayout.php';

    public function beforeAction(){
        $this->api = Context::getInstance()->getApiManager()->getApi();
    }

    public function index(){
        $user = Context::getInstance()->getUser()->getUser();

        $defaultFolders = $customFolders = [];

        $folders = $this->api->getUserFolders('candidate', $user['id']);
        foreach($folders as $item) {
            if ($item['user_id']){
                $customFolders[] = $item;
            } else {
                $defaultFolders[] = $item;
            }
        }
        $this->user_id = $user['id'];
        $this->role = 'candidate';
        $this->userRole = 'organization';
        $this->default = $defaultFolders;
        $this->custom = $customFolders;
    }

    public function add(){

        if( !isset($_POST['role']) || !isset($_POST['folder']) || !isset($_POST['user_id'])){
            $this->forward404();
        }

        $response = $this->api->addFolder($_POST['role'], $_POST['folder'], $_POST['user_id']);

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
}