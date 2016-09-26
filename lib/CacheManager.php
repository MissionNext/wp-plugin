<?php


namespace MissionNext\lib;


class CacheManager {

    public function set($key, $value, $expiration = 0, $local = true){

        if($local){
            return set_transient($key, $value, $expiration);
        } else {
            return set_site_transient($key, $value, $expiration);
        }

    }

    public function get($key, $local = true){

        if($local){
            return get_transient($key);
        } else {
            return get_site_transient($key);
        }

    }

    public function remove($key, $local = true){

        if($local){
           return delete_transient($key);
        } else {
           return delete_site_transient($key);
        }

    }

} 