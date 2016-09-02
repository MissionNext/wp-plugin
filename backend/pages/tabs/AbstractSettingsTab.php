<?php

namespace MissionNext\backend\pages\tabs;


abstract class AbstractSettingsTab {

    public $label;

    protected $errors = array();
    protected $notifications = array();

    public function __construct( $label ){
        $this->label = $label;
    }

    function addError($key, $message){
        $this->errors[$key] = $message;
    }

    function removeError($key){
        if(isset($this->errors[$key])){
            unset($this->errors[$key]);
        }
    }

    function clearErrors(){
        $this->errors = array();
    }

    function printErrors(){

        if(empty($this->errors)){
            return;
        }

        ?>
        <div class="error">
            <ul>
                <?php foreach($this->errors as $key => $error): ?>
                <li id="error-<?php echo $key ?>" ><?php echo $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
<?php

    }

    function addNotice($key, $message){
        $this->notifications[$key] = $message;
    }

    function removeNotice($key){
        if(isset($this->notifications[$key])){
            unset($this->notifications[$key]);
        }
    }

    function clearNotices(){
        $this->notifications = array();
    }

    function printNotices(){

        if(empty($this->notifications)){
            return;
        }

        ?>
        <div class="updated">
            <ul>
                <?php foreach($this->notifications as $key => $notice): ?>
                    <li id="notice-<?php echo $key ?>" ><?php echo $notice ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php

    }

    function addedToPage(){}

    function initTab(){}

    function printTab(){

        ?>

        <h2><?php echo $this->label?></h2>

        <?php $this->printErrors() ?>

        <?php $this->printNotices() ?>

        <?php $this->printContent() ?>

    <?php

    }

    abstract function printContent();

} 