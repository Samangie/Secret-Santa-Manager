<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 10:39
 */
include_once 'Core/Controller/ComponentController.php';
class HomeController extends ComponentController
{
    public function index()
    {
        $this->output("home", "index");
    }
}