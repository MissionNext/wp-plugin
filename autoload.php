<?php

require_once "lib/api/vendor/autoload.php";
require_once "pluggable.php";

spl_autoload_register('MNAutoload');

function MNAutoload($class){
    if(strpos($class, 'MissionNext') === 0){
        $class = str_replace('\\', '/', $class);
        require_once (__DIR__."/../".$class.'.php');
    }
}
