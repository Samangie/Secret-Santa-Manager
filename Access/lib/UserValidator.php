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
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function isValid(string $reppasword = '')
    {
        $checkuniqueUsername = $this->uniqueUsername($this->model->getUsername());
        $checkPasswordIsValid = $this->passwordIsValid($this->model->getPassword());
        $checkComparePasswords = $this->comparePasswords($this->model->getPassword(), $reppasword);
        $checkEmailIsValid = $this->emailIsValid($this->model->getEmail());
        $checkEmailIsUnique = $this->uniqueEmail($this->model->getEmail());

        if ($checkuniqueUsername
            && $checkPasswordIsValid
            && $checkComparePasswords
            && $checkEmailIsValid
            && $checkEmailIsUnique
        ) {
            return true;
        }
        return false;
    }

    public function uniqueUsername(string $username, bool $setMessage = true)
    {
        if ($this->model->readByAttribut('username', $username)) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Der Benutzername exisitert bereits!';
            $this->setErrorMessages('unique_username', $errorMessage);
        }
        return false;
    }

    public function passwordIsValid(string $password, bool $setMessage = true)
    {
        if (preg_match('^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$@#!?%*&])[a-zA-Z-0-9$@#!?%*&]{8,25}^', $password)){
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Das Passwort muss zwischen 8 - 25 zeichen lang sein und ein Sonderzeichen sowie eine Zahl enthalten!';
            $this->setErrorMessages('not_valid_password', $errorMessage);
        }
        return false;
    }

    public function comparePasswords(string $password, string $reppassword, bool $setMessage = true)
    {
        if ($password == $reppassword) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Die Passwörter stimmen nicht überein.';
            $this->setErrorMessages('different_passwords', $errorMessage);
        }
        return false;
    }

    public function emailIsValid(string $email, bool $setMessage = true)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Muss eine E-Mail Adresse sein!';
            $this->setErrorMessages('not_valid_email', $errorMessage);
        }
        return false;

    }

    public function uniqueEmail(string $email, bool $setMessage = true)
    {
        if ($this->model->readByAttribut('email', $email)) {
            return true;
        }
        if ($setMessage) {
            $errorMessage = 'Die Email wird bereits verwendet!';
            $this->setErrorMessages('unique_email', $errorMessage);
        }
        return false;

    }

    public function userDoesntExist()
    {
        $errorMessage = 'Der Benutzername und das Passwort stimmt nicht überein!';
        $this->setErrorMessages('user_doesnt_exist', $errorMessage);
    }

}