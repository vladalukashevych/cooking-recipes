<?php

namespace core;

use controllers\HomeController;

class Core
{
    private static ?Core $instance = null;
    public array $app;
    public $pageParams;
    public DB $db;
    public $requestMethod;

    private function __construct()
    {
        global $pageParams;
        $this->app = [];
        $this->pageParams = $pageParams;
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Initialize()
    {
        session_start();
        $this->db = new DB(DATABASE_HOST, DATABASE_LOGIN,
            DATABASE_PASSWORD, DATABASE_BASENAME);
    }

    public function Run()
    {
        $route = $_GET['route'];
        $routeParts = explode('/', $route);
        $moduleName = strtolower(array_shift($routeParts));
        if (empty($moduleName))
            $moduleName = 'home';
        $actionName = strtolower(array_shift($routeParts));
        if (empty($actionName))
            $actionName = 'index';
        $this->app['moduleName'] = $moduleName;
        $this->app['actionName'] = $actionName;
        $controllerName = '\\controllers\\' . ucfirst($moduleName) . 'Controller';
        $controllerActionName = $actionName . 'Action';
        $statusCode = 200;
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $controllerActionName)) {
                $actionResult = $controller->$controllerActionName($routeParts);
                if ($actionResult instanceof Error)
                    $statusCode = $actionResult->code;
                $this->pageParams['content'] = $actionResult;
            } else {
                $statusCode = 404;
            }
        } else {
            $statusCode = 404;
        }
        $statusCodeType = intval($statusCode / 100);
        if ($statusCodeType == 4 || $statusCodeType == 5) {
            $homeController = new HomeController();
            $this->pageParams['content'] = $homeController->errorAction($statusCode);
        }
    }

    public function Done()
    {
        $pathToLayout = 'themes/light/layout.php';
        $tpl = new Template($pathToLayout);
        $tpl->setParams($this->pageParams);
        $html = $tpl->getHTML();
        echo $html;
    }

}