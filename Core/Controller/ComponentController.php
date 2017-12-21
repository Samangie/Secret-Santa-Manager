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

    protected function output($controllerName,$methodName, $placeholders = null, $placeholderContent = null)
    {
        new View($controllerName,$methodName, $placeholders, $placeholderContent);
    }

}