<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 16:07
 */

require_once 'Core/lib/Validator.php';
require_once 'Access/Model/User.php';

class UserValidator extends Validator
{
    protected $model;

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function isValid($reppasword = null)
    {
        $checkuniqueUsername = $this->uniqueUsername($this->model->username);
        $checkUsernameIsString = $this->valueIsString($this->model->username);
        $checkPasswordIsValid = true; //$this->passwordIsValid($this->model->password);
        $checkComparePasswords = $this->comparePasswords($this->model->password, $reppasword);
        $checkEmailIsValid = $this->emailIsValid($this->model->email);
        $checkEmailIsUnique = $this->uniqueEmail($this->model->email);

        if ($checkuniqueUsername
            && $checkUsernameIsString
            && $checkPasswordIsValid
            && $checkComparePasswords
            && $checkEmailIsValid
            && $checkEmailIsUnique
        ) {
            return true;
        }
    }

    public function uniqueUsername($username)
    {
        if ($this->model->readByAttribut('username', $username)) {
            return true;
        }

        $this->errorMessages .= 'Der Benutzername exisitert bereits!';
        return false;
    }

    public function passwordIsValid($password)
    {
        //"^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}"
        if (preg_match('', $password)){
            return true;
        }

        $this->errorMessages .= 'Das Passwort muss zwischen 8 - 25 zeichen lang sein und ein Sonderzeichen sowie eine Zahl enthalten!';

    }

    public function comparePasswords($password, $reppassword)
    {
        if ($password == $reppassword) {
            return true;
        }

        $this->errorMessages .= 'Die Passwörter stimmen nicht überein.';
        return false;
    }

    public function emailIsValid($email)
    {
        $regex = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

        if (preg_match($regex, $email)) {
            return true;
        }

        $this->errorMessages .= 'Muss eine E-Mail Adresse sein!';

    }

    public function uniqueEmail($email)
    {
        if ($this->model->readByAttribut('email', $email)) {
            return true;
        }

        $this->errorMessages .= 'Die Email wird bereits verwendet!';
        return false;

    }

}