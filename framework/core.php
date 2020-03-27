<?php

spl_autoload_register(function ($name) {
    include __DIR__ . DS . 'core/' . $name . '.php';
});

class core
{
    private static $app = false;
    private $config = [];
    public $input = false;
    public $user = false;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}
    private function __sleep(){}
    
    public function __get($key)
    {
        return $this->config[$key] ?? null;
    }
    
    public static function app()
    {
        if (self::$app === false) {
            self::$app = new self;
        }
        return self::$app;
    }
    
    public function run($config)
    {
        $this->config = $config;
        $this->input = new inputs;
        $this->user = new user;

        try {
            $this->runController(
                $this->input->controller,
                $this->input->action
            );
        } catch (httpException $e) {
            header("HTTP/1.1 " . $e->statuses[$e->getCode()]);
            $this->runController(core::app()->error_controller, core::app()->error_action);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function runController($controller, $action)
    {
        if (!preg_match('#^[a-zA-Z0-9_]{1,50}$#', $controller) || !preg_match('#^[a-zA-Z0-9_]{1,50}$#', $action)) {
            throw new httpException(
                'Controller or action name is wrong: ' . $controller . '->' . $action, 
                404
            );
        }
        $controllerPath = core::app()->controllers_dir . $controller . '.controller.php';
        if (!file_exists($controllerPath)) {
            throw new httpException(
                'Controller file not found: ' . $controller, 
                404
            );
        }
        include $controllerPath;
        $controllerClass = 'controller' . ucfirst(strtolower($controller));
        $controllerObj = new $controllerClass();
        $actionName = 'action' . ucfirst(strtolower($action));
        if (!method_exists($controllerObj, $actionName)) {
            throw new httpException(
                'Action ' . $action . ' not found in controller ' . $controller, 
                404
            );
        }
        $controllerObj->beforeAction();
        $beforeActionName = 'before' . ucfirst(strtolower($action));
        if (method_exists($controllerObj, $beforeActionName)) {
            $controllerObj->$beforeActionName();
        }
        
        
        ob_start();
        $outputData = $controllerObj->$actionName();
        if (is_array($outputData)) {
            ob_end_clean();
        } else {
            $outputData = ob_get_clean();
        }
        

        $controllerObj->afterAction();
        $controllerObj->response($outputData);
    }
}