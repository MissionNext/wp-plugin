<?php

namespace MissionNext\lib\core;

class ResourceManager {

    public function __construct(){
    }


    /**
     * @param String $name Unique resource name
     * @param bool $path URL or path relative to plugin resources/js/ dir
     * @param array $deps Dependencies
     */
    public static function addJSResource($name, $path = false, $deps = array(), $ver = false, $in_footer = false){
        wp_enqueue_script($name, strpos($path, 'http') === 0? $path : MN_PLUGIN_URL.'/resources/js/'.$path, $deps, $ver, $in_footer);
    }


    /**
     * @param String $name Unique resource name
     * @param bool $path URL or path relative to plugin resources/css/ dir
     * @param array $deps Dependencies
     */
    public static function addCSSResource($name, $path = false, $deps = array(), $ver = false, $media = 'all' ){
        wp_enqueue_style($name, strpos($path, 'http') === 0? $path : MN_PLUGIN_URL.'/resources/css/'.$path, $deps, $ver, $media );
    }

    public static function getImageUrl($path){
        return MN_PLUGIN_URL . '/resources/images/'.$path;
    }

} 