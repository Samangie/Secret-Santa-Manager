<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 15:12
 */

class Validator
{
    protected $model;
    protected $errorMessagesArray = array();

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function setErrorMessages(string $identificator, string $message)
    {
        $this->errorMessagesArray[$identificator] = $message;
        $_SESSION['errorMessages'] = $this->errorMessagesArray;
    }
}