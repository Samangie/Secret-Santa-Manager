<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 13:10
 */


require_once 'Core/View/View.php';

require_once 'Home/Controller/HomeController.php';
require_once 'Campaign/Controller/CampaignController.php';
require_once 'Access/Controller/AccessController.php';

class MainController
{
    public function __construct()
    {
        session_start();
        $this->main();
    }

    public function main()
    {
        $controllerName = 'home';
        $methodName = 'index';

        $request = explode('/', $_SERVER['REQUEST_URI']);
        if (!empty($request[1])) {
            $controllerName = strtolower ($request[1]);
        }
        if (!empty($request[2])) {
            $methodName = explode("?", $request[2]);
            $methodName = strtolower ($methodName[0]);
        }

        $controllerClass = ucfirst($controllerName) . 'Controller';

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists ($controller, $methodName)) {
                $controller->$methodName();
            }
        }
    }

}