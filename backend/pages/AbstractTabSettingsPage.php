<?php

namespace MissionNext\backend\pages;

use MissionNext\backend\pages\tabs\AbstractSettingsTab;

abstract class AbstractTabSettingsPage extends AbstractSettingsPage{

    /**
     * @var AbstractSettingsTab[]
     */
    protected $tabs = array();
    protected $currentTab;

    public function addPage(){

        parent::addPage();

        foreach($this->tabs as $tab){
            $tab->addedToPage();
        }

    }

    public function pageInit(){
        parent::pageInit();

        reset($this->tabs);
        $this->currentTab = isset($_GET['tab'])?$_GET['tab']:key($this->tabs);

        if( $this->currentTab && isset( $this->tabs[ $this->currentTab ] )){
            $this->tabs[ $this->currentTab ]->initTab();
        }

    }

    public function setTabs($tabs){
        $this->tabs = $tabs;
    }

    public function getTabs(){
        return $this->tabs;
    }

    public function hasTabs(){
        return !empty($this->tabs);
    }

    public function printContent(){

        if($this->hasTabs()){

            ?>
            <h2 class="nav-tab-wrapper">
            <?php
            $this->printTabsMenu();
            ?>
            </h2>

            <?php

            $this->printCurrentTab();

        }

    }

    protected function printTabsMenu(){

        foreach($this->tabs as $tabKey => $tab){
            ?>
                <a class="nav-tab<?php if($this->currentTab == $tabKey) echo ' nav-tab-active' ?>" href="?page=<?php echo $this->menu_slug ?>&tab=<?php echo $tabKey ?>"><?php echo $tab->label ?></a>
            <?php
        }
    }

    protected function printCurrentTab(){

        if( isset( $this->tabs[ $this->currentTab ] )){
            $this->tabs[ $this->currentTab ]->printTab();
        }

    }

}