<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 13:41
 */


class View
{
    public function display($controllerName, $methodName) {
        $config = require_once 'config.php';
        require_once "themes/" . $config['themeName'] . "/header.php";
        require_once "themes/" . $config['themeName'] . "/footer.php";

        $path = "themes/" . $config['themeName'] . "/" . $controllerName . "/" . $controllerName . "_" . $methodName . ".php";
        if(file_exists($path)) {
            include_once("themes/" . $config['themeName'] . "/header.php");
            include_once($path);
            include_once("themes/" . $config['themeName'] . "/footer.php");
        } else {
            include ("themes/" . $config['themeName'] . "/error.php");
        }
    }

}