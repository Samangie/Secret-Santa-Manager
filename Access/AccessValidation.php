<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 16:07
 */

require_once "Core/lib/Validation.php";
require_once "Access/Model/Access.php";

class UserValidation extends Validation
{

    public function passwordIsValid($password){

        if(preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_%=^&+$!\?]{8,25}$/', $password)){
            return true;
        }

        $_SESSION['passwordIsNotValid'] = "Das Passwort muss zwischen 8 - 25 zeichen lang sein und ein Sonderzeichen sowie eine Zahl enthalten!";

    }

    public function emailIsValid($email) {

        $regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

        if(preg_match($regex, $email)) {
            return true;
        }

    }

    public function uniqueUsername($username) {

        $user = new User();
        if($user->readByAttribut('username', $username)) {
            return true;
        }

        $_SESSION['userExist'] = "Der Benutzername exisitert bereits!";
        return false;

    }

    public function uniqueEmail($email) {

        $user = new User();
        if($user->readByAttribut('email', $email)) {
            return true;
        }

        $_SESSION['userExist'] = "Die Email wird bereits verwendet!";
        return false;

    }

    public function comparePasswords($password, $reppassword) {

        if($password == $reppassword) {
            return true;
        }

        $_SESSION['differentPassword'] = "Die Passwörter stimmen nicht überein.";
        return false;
    }

}