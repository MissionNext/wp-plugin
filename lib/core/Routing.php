<?php

namespace MissionNext\lib\core;

class Routing {

    public $route;

    private static $INSTANCE;

    private $routes;
    private $app;

    public static function register($app = 'frontend'){
        if( null == self::$INSTANCE ){
            self::$INSTANCE = new self($app);
        }
    }

    /**
     * @return self
     */
    public static function getInstance(){
        return self::$INSTANCE;
    }

    public function __construct($app){

        $this->app = $app;
        $this->routes = $this->getConfig();

        add_action("wp_ajax_mn", array($this, 'check'));
        add_action("template_redirect", array($this, 'check'), 0);
    }

    public function getConfig(){
       return Context::getInstance()->getConfigManager()->load('routing', $this->app, true);
    }

    public function check(){

        global $wp_query;

        if(isset($_GET['route'])){
            $plain = $_GET['route'];
        } elseif(isset($_POST['route'])){
            $plain = $_POST['route'];
        } else {
            $plain = $_SERVER['REQUEST_URI'];
        }

        if(($pos = strpos($plain, '?')) !== false){
            $plain = substr($plain, 0, $pos);
        }

        $route = $plain?$this->matchRoute($plain):null;

        if( $route ){

            $this->route = $route;

            if ($wp_query->is_404) {
                $wp_query->is_404 = false;
            }
            header("HTTP/1.1 200 OK");

            $this->execute($route);
        }
    }

    private function execute($route){

        $controllerName = '\MissionNext\\'. $this->app .'\controllers\\'.$route['controller'].'Controller';
        $actionName = $route['action'];

        /**
         * @var $controller Controller
         */
        $controller = new $controllerName();

        foreach($controller->before_filters as $filter){
            call_user_func($filter, $controller);
        }

        $controller->beforeAction();

        $templateName = $controller->$actionName($route['params']);

        $controller->afterAction();

        foreach($controller->after_filters as $filter){
            call_user_func($filter, $controller);
        }

        $vars = get_object_vars($controller);

        $site = 0;
        $configs = \MissionNext\lib\core\Context::getInstance()->getApiManager()->getApi()->getSubscriptionConfigs();
        $publicKey = \MissionNext\lib\core\Context::getInstance()->getApiManager()->publicKey;
        foreach($configs as $config){
            if($config['public_key'] == $publicKey){
                $site = $config['id'];
            }
        }

        $vars = array_merge($vars, ['site' => $site]);

        if($templateName === null){
            $templateName = $route['controller'].'/'.$route['action'].'.php';
        } elseif($templateName === false){
            exit;
        }

        $this->renderTemplate($templateName, $vars);
    }

    private function matchRoute($plain){

        foreach($this->routes as $pattern => $route){

            if(strpos($pattern, '(') !== false){

                if(preg_match("#^$pattern$#i", $plain, $params)){
                    unset($params[0]);
                    $route['params'] = array_values($params);
                    return $route;
                }

            } elseif(isset($this->routes[$plain])) {
                $route = $this->routes[$plain];
                $route['params'] = array();
                return $route ;
            }
        }

    }

    private function renderTemplate($mn_routing_template_name, $vars){

        extract($vars);

        if(isset($layout) && $layout){
            ob_start();
            include( $this->getTemplatePath( $mn_routing_template_name ) );

            $content = ob_get_clean();

            include($this->getTemplatePath($layout));

        } else {
            include( $this->getTemplatePath( $mn_routing_template_name ) );
        }

        exit;
    }

    private function getTemplatePath($name){
        return MN_ROOT_DIR . '/'.$this->app.'/templates/' . $name;
    }

} 