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
    protected $errorMessages;

    public function __construct($model) {
        $this->model = $model;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    public function valueIsInteger($value, $lengthMax = 11, $lengthMin = 1)
    {
        if (is_int ($value) && strlen ($value) <= $lengthMax && strlen ($value) >= $lengthMin) {
            return true;
        }

        $this->errorMessages .= 'Die Eingabe muss eine Zahl zwischen sein!';

        return false;
    }

    public function valueIsString($value, $lengthMax = 50, $lengthMin = 1)
    {
        if (is_string ($value) && strlen ($value) <= $lengthMax && strlen ($value) >= $lengthMin) {
            return true;
        }

        $this->errorMessages .= 'Die Eingabe muss ein String sein!';

        return false;
    }

    public function valueIsDate($value)
    {
        if (strtotime($value)) {
            return true;
        }

        $this->errorMessages .= 'Die Eingabe muss ein Datum sein!';

        return false;
    }
}

