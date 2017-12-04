<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 13:41
 */

namespace Core\View;

class View
{
    public function display($controllerName, $methodName) {
        $config = require_once 'config.php';

        $path = "themes/" . $config['themeName'] . "/" . $controllerName . "/" . $controllerName . "_" . $methodName . ".php";
        if(file_exists($path)) {
            include($path);
        } else {
            include ("themes/" . $config['themeName'] . "/error.php");
        }
    }

}