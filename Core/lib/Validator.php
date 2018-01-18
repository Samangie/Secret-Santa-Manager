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

    public function __construct($model) {
        $this->model = $model;
    }

    public function setErrorMessages($identificator, $message) {
        $this->errorMessagesArray[$identificator] = $message;
        $_SESSION['errorMessages'] = $this->errorMessagesArray;
    }

    public function valueIsInteger($value, $lengthMax = 11, $lengthMin = 1, $setMessage = true)
    {
        if (is_int ($value) && strlen ($value) <= $lengthMax && strlen ($value) >= $lengthMin) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Die Eingabe muss eine Zahl zwischen sein!';
            $this->setErrorMessages('value_is_not_integer', $errorMessage);
        }
        return false;
    }

    public function valueIsString($value, $lengthMax = 50, $lengthMin = 1, $setMessage = true)
    {
        if (is_string ($value) && strlen ($value) <= $lengthMax && strlen ($value) >= $lengthMin) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Die Eingabe muss ein String sein!';
            $this->setErrorMessages('value_is_not_string', $errorMessage);
        }

        return false;
    }

    public function valueIsDate($value, $setMessage = true)
    {
        if (strtotime($value)) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Die Eingabe muss ein Datum sein!';
            $this->setErrorMessages('value_is_not_date', $errorMessage);
        }
        return false;
    }
}