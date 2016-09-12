<?php


namespace MissionNext\lib;


class DefaultDataManager {

    public $default_pages = array(
        Constants::PAGE_BLOCKED,
        Constants::PAGE_PENDING_APPROVAL,
        Constants::PAGE_NO_AGENCY_ROLE
    );

    public function __construct(){
        add_action( 'wpmu_new_blog', array( $this, 'createDefault' ) );
    }

    public function createDefaults(){

        $sites = wp_get_sites();

        foreach($sites as $site){
            $this->createDefault($site['blog_id']);
        }

    }

    public function removeDefaults(){

        $sites = wp_get_sites();

        foreach($sites as $site){
            $this->removeDefault($site['blog_id']);
        }

    }

    public function removeDefault($blog_id){

        switch_to_blog($blog_id);

        foreach($this->default_pages as $page){

            $page = get_page_by_path($page);

            if($page){
                wp_delete_post($page->ID, true);
            }
        }

        restore_current_blog();
    }

    public function createDefault($blog_id){

        $default_pages = $this->default_pages;

        switch_to_blog($blog_id);

        if ( $current_pages = get_pages() )
            $default_pages = array_diff( $default_pages, wp_list_pluck( $current_pages, 'post_name' ) );

        foreach($default_pages as $page){
            wp_insert_post(array(
                'post_title'   => ucwords(str_replace('_', ' ', $page)),
                'post_name'    => $page,
                'post_content' => file_get_contents(MN_ROOT_DIR . '/data/defaults/' . $page . '.txt'),
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ));

        }

        restore_current_blog();
    }

} 