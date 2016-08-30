<?php

namespace MissionNext\backend\pages;

abstract class AbstractSettingsPage{

    protected $menu_title;
    protected $page_title;
    protected $capability;
    protected $menu_slug;
    protected $parent_slug;

    /**
     * Start up
     */
    public function __construct($menu_title, $page_title, $capability, $menu_slag = '', $parent_slug = 'mission_next')
    {
        $this->menu_title = $menu_title;
        $this->page_title = $page_title;
        $this->capability = $capability;
        if(!$menu_slag){
            $menu_slag = $parent_slug . '_' . strtolower(str_replace(' ', '_', $menu_title));
        }
        $this->menu_slug = $menu_slag;
        $this->parent_slug = $parent_slug;

        add_action( 'admin_menu', array( $this, 'addPage' ) );

        if(isset($_GET['page']) && $_GET['page'] == $this->menu_slug){
            add_action( 'admin_init', array( $this, 'pageInit' ) );
        }
    }

    /**
     * Add options page
     */
    public function addPage()
    {
        add_submenu_page(
            $this->parent_slug,
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            array( $this, 'printPage' )
        );
    }

    abstract function printContent();

    public function pageInit(){}

    public function beforePagePrint(){}

    public function afterPagePrint(){}

    public function printPage()
    {
        $this->beforePagePrint();

        ?>
        <div class="wrap">

            <?php $this->printContent() ?>

        </div>
    <?php

        $this->afterPagePrint();
    }

}