<?php


namespace MissionNext\frontend\controllers;

class favoriteController extends AbstractLayoutController {

    public function show(){

        $this->layout = 'sidebarLayout.php';

        if($this->userRole == 'candidate'){
            $this->org_favorites = $this->api->getFavorites($this->userId, 'organization');
            $this->job_favorites = $this->api->getFavorites($this->userId, 'job');
        } else {
            $this->favorites = $this->api->getFavorites($this->userId, 'candidate');
            $foldersApi = $this->api->getUserFolders('candidate', $this->userId);
            $default_folder_id = \MissionNext\lib\SiteConfig::getDefaultFolder('candidate');
            $default_folder = '';

            $folders = array();

            foreach($foldersApi as $folderApi){

                if($folderApi['id'] == $default_folder_id){
                    $default_folder = $folderApi['title'];
                    $folders = array_merge(array($folderApi['title'] => $folderApi['value']?$folderApi['value']:$folderApi['title']), $folders);
                } else {
                    $folders[$folderApi['title']] = $folderApi['value']?$folderApi['value']:$folderApi['title'];
                }
            }

            $this->folders = $folders;
        }

        return 'favorite/' . $this->userRole . '.php';
    }

    public function add(){

        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !$this->userId
            || !isset($_POST['id'])
            || !isset($_POST['role'])
        ){
            $this->forward404();
        }

        $response = $this->api->addFavorite($this->userId, $_POST['role'], $_POST['id']);

        echo json_encode($response);

        return false;
    }

    public function remove(){

        if($_SERVER['REQUEST_METHOD'] != 'POST'
            || !isset($_POST['id'])
        ){
            $this->forward404();
        }

        $response = $this->api->removeFavorite($_POST['id']);

        echo json_encode($response);

        return false;
    }


} 