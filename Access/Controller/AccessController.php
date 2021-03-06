<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

require_once 'Access/Model/User.php';
require_once 'Access/lib/UserValidator.php';
include_once 'Core/lib/Placeholder.php';

class AccessController extends ComponentController
{
    public function index()
    {
        if (!empty($_SESSION['loggedin'])) {
            header('Location: /');
        };
        if (!empty($_SESSION['errorMessages'])) {
            $errorMessages = $_SESSION['errorMessages'];
        } else {
            $errorMessages = array();
        }
        $placeholders = array(
            new Placeholder('ERROR_LOGIN', 'errorMessagesLogin_content', '',
                array(
                    'USER_DOESNT_EXIST'
                ), $errorMessages
            ),
            new Placeholder('ERROR_REGISTRATION', 'errorMessagesRegistration_content', '',
                array(
                    'UNIQUE_USERNAME',
                    'NOT_VALID_PASSWORD',
                    'DIFFERENT_PASSWORDS',
                    'NOT_VALID_EMAIL',
                    'UNIQUE_EMAIL',
                ), $errorMessages
            ),
        );
        $this->output('access','index', $placeholders);

    }

    public function login()
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }

        $_SESSION['errorMessages'] = '';

        $validator = new UserValidator(new User(0,$username, $password));
        if ($validator->emailIsValid($username, false)) {
            $user = new User(0, '', $password, $username);
        } else {
            $user = new User(0, $username, $password);
        }

        if ($user->login()) {
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role'] = $user->getRole();
            $_SESSION['loggedin'] = true;

            header('Location: /Campaign/');
        } else {
           $validator->userDoesntExist();
        }

        header("Location: /Access/");
    }

    public function register()
    {
        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppassword = $_POST['reppassword'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            $_SESSION['errorMessages'] = '';

            $user = new User(0, $username, $password, $email, $role);

            $validator = new UserValidator($user);
            $validator->isValid($reppassword);
            if (empty($_SESSION['errorMessages'])) {
                if ($user->insert()) {
                    $_SESSION['username'] = $user->getUsername();
                    $_SESSION['role'] = $user->getRole();
                    $_SESSION['loggedin'] = true;
                    header('Location: /Campaign/');
                }
            }
            header('Location: /Access/');
        } else {
            header('Location: /Access/');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['username'])) {
            $user = new User(0, $_SESSION['username']);
            $user->logout();
        }
        header('Location: /');
    }
}