<?php


namespace MissionNext\lib\core;
use MissionNext\lib\AdminMailService;
use MissionNext\lib\ApiManager;
use MissionNext\lib\AvatarManager;
use MissionNext\lib\CacheManager;
use MissionNext\lib\CustomTranslationsManager;
use MissionNext\lib\GlobalConfigManager;
use MissionNext\lib\LocalizationManager;
use MissionNext\lib\MailService;
use MissionNext\lib\PluginActivationManager;
use MissionNext\lib\RoutineManager;
use MissionNext\lib\SiteConfigManager;
use MissionNext\lib\UserConfigManager;

/**
 * Class Context
 * @package MissionNext\lib\core
 *
 * Data pool for application
 *
 * @method CustomTranslationsManager getCustomTranslationsManager
 * @method PluginActivationManager getActivationManager
 * @method LocalizationManager getLocalizationManager
 * @method GlobalConfigManager getGlobalConfigManager
 * @method SiteConfigManager getSiteConfigManager
 * @method AdminMailService getAdminMailService
 * @method FlashDataManager getFlashDataManager
 * @method TemplateService getTemplateService
 * @method ResourceManager getResourceManager
 * @method RoutineManager getRoutineManager
 * @method ConfigManager getConfigManager
 * @method UserConfigManager getUserConfigManager
 * @method AvatarManager getAvatarManager
 * @method CacheManager getCacheManager
 * @method MailService getMailService
 * @method ApiManager getApiManager
 * @method Config getConfig
 * @method Logger getLogger
 * @method User getUser
 *
 * @method setCustomTranslationsManager
 * @method setLocalizationManager
 * @method setGlobalConfigManager
 * @method setSiteConfigManager
 * @method setActivationManager
 * @method setAdminMailService
 * @method setFlashDataManager
 * @method setTemplateService
 * @method setResourceManager
 * @method setRoutineManager
 * @method setAvatarManager
 * @method setConfigManager
 * @method setUserConfigManager
 * @method setCacheManager
 * @method setMailService
 * @method setApiManager
 * @method setConfig
 * @method setLogger
 * @method setUser
 */
class Context {

    private static $INSTANCE;

    private $type;
    private $pool;

    private $contextPool = array(

        'ConfigManager' => array(
            'class' => '\\MissionNext\\lib\\core\\ConfigManager'
        ),
        'Config' => array(
            'class' => '\\MissionNext\\lib\\core\\Config'
        ),
        'CacheManager' => array(
            'class' => '\\MissionNext\\lib\\CacheManager'
        ),
        'Logger' => array(
            'class' => '\\MissionNext\\lib\\core\\Logger'
        ),
        'ApiManager' => array(
            'class' => '\\MissionNext\\lib\\ApiManager'
        ),
        'TemplateService' => array(
            'class' => '\\MissionNext\\lib\\core\\TemplateService'
        ),
        'ResourceManager' => array(
            'class' => '\\MissionNext\\lib\\core\\ResourceManager'
        ),
        'HelperManager' => array(
            'class' => '\\MissionNext\\lib\\core\\HelperManager'
        ),
        'RoutineManager' => array(
            'class' => '\\MissionNext\\lib\\RoutineManager'
        ),
        'DefaultDataManager' => array(
            'class' => '\\MissionNext\\lib\\DefaultDataManager'
        ),
        'ActivationManager' => array(
            'class' => '\\MissionNext\\lib\\PluginActivationManager'
        )
    );

    private $defaultPool = array();

    /**
     * @return self
     */
    public static function getInstance(){
        if(self::$INSTANCE == null){
            self::create();
        }

        return self::$INSTANCE;
    }

    public static function create(){
        self::$INSTANCE = new self();
        self::$INSTANCE->init();
    }

    public function init(){

        $this->populateContext();

        add_action( 'init' , array( $this, 'initialize') );
    }

    public function initialize(){

        $this->type = (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) || is_admin())?'backend':'frontend';

        $this->defaultPool = ConfigManager::get('factories');

        $this->populateDefault();

        include MN_ROOT_DIR."/config/bootstrap.php";
        include MN_ROOT_DIR.'/'.$this->type.'/config/bootstrap.php';
    }

    public function getType(){
        return $this->type;
    }

    public function get($key, $default = null){
        return $this->has($key)?$this->pool[$key]:$default;
    }

    public function has($key){
        return isset($this->pool[$key]);
    }

    public function set($key, $value){
        $this->pool[$key] = $value;
    }

    private function populateContext(){
        foreach($this->contextPool as $key => $config){
            $this->set($key, new $config['class']());
        }
    }

    private function populateDefault(){
        foreach($this->defaultPool as $key => $config){
            $this->set($key, new $config['class']());
        }
    }

    public function __call( $name , array $arguments ){

        if(strpos($name, 'get') === 0){
            return $this->get(substr($name, 3));
        } else if(strpos($name, 'set') === 0){
            $this->set(substr($name, 3), @$arguments[0]);
        } else if(strpos($name, 'has') === 0){
            return $this->has(substr($name, 3));
        }

    }

} 