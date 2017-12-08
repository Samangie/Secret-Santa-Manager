<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 16:00
 */


abstract class ComponentController
{
    abstract protected function index();

    protected function output($uri){

        $names = explode("_", $uri);
        $view = new View();
        $view->display($names[0], names[1]);

    }
}