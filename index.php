<?php

require_once 'Core/Controller/MainController.php';
require_once 'Core/Model/Model.php';

session_start();

$mainController = new MainController();
$mainController->main();
