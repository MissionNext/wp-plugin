<?php
/**
 * Plugin Name: MissionNext
 * Description: Connector to MissionNext api
 * Version: 0.1
 * Text Domain: mission-next
 * Domain Path: /data/languages
 */


define('MN_ROOT_DIR', __DIR__);
define('MN_PLUGIN_URL', plugins_url('', __FILE__));
define('MN_PLUGIN_FILE', __FILE__);

require_once "autoload.php";

\MissionNext\lib\core\Context::create();

//add_action('init', array('MissionNext\lib\core\Context', 'create'));
