<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 16:00
 */


abstract class ComponentController
{
    abstract protected function __construct();

    protected function output($controllerName,$methodName, $dataFromDB = null)
    {
        $view = new View();
        $view->entries = $dataFromDB;
        $view->display($controllerName,$methodName, $dataFromDB);
    }

}