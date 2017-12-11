<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 15:12
 */
class Validation
{

    public function valueIsInteger($value, $lengthMax = 11, $lengthMin = 1) {

        if(is_int ($value) && $value.length <= $lengthMax && $value.length >= $lengthMin) {
            return true;
        }

        $_SESSION['valueIsNotAnValidInteger'] = "Die Eingabe muss eine Zahl zwischen sein!";

        return false;
    }

    public function valueIsString($value, $lengthMax = 50, $lengthMin = 1) {

        if(is_string ($value) && $value.length <= $lengthMax && $value.length >= $lengthMin) {
            return true;
        }

        $_SESSION['valueIsNotAValidString'] = "Die Eingabe muss ein String sein!";

        return false;

    }

    public function valueIsDate($value) {

        if(strtotime($value)) {
            return true;
        }

        $_SESSION['valueIsNotAValidDate'] = "Die Eingabe muss ein Datum sein!";

        return false;

    }
}

