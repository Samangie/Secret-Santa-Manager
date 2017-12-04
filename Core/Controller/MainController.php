<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 13:10
 */

namespace Core\Controller;

//require_once 'Core/View/View.php';

class MainController
{
    public function main() {

        $controllerName = "home";
        $methodName = "index";

        $request = explode("/", $_SERVER['REQUEST_URI']);
        if(isset($request[1]) && !empty($request[1])) { $controllerName = strtolower ($request[1]); };
        if(isset($request[2]) && !empty($request[2])) { $methodName = strtolower ($request[2]); };

        $cn = 'Campaign\Controller\CampaignController';
        $c = new \Campaign\Controller\CampaignController();
        echo 'echoo \\';
        $controllerClass = '\\' . ucfirst($controllerName) . "\\Controller\\" . ucfirst($controllerName) . 'Controller';
        echo $controllerClass;
        if(include($controllerClass)) {

            $controller = new $controllerClass();

            if(method_exists ($controller, $methodName)) {
                $controller->$methodName();
            }
        } else {
            $view = new \Core\View\View();
            $view->display($controllerName,$methodName);
        }
    }

}