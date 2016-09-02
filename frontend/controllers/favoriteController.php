<?php


namespace MissionNext\frontend\controllers;

class favoriteController extends AbstractLayoutController {

    public function show(){

        $this->layout = 'sidebarLayout.php';

        if($this->userRole == 'candidate'){
            $this->org_favorites = $this->api->getFavorites($this->userId, 'organization');
            $this->job_favorites = $this->api->getFavorites($this->userId, 'job');
        }
        else
            $this->favorites = $this->api->getFavorites($this->userId, 'candidate');

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