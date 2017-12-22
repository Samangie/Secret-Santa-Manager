<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

require_once 'Access/Model/User.php';
require_once 'Access/lib/UserValidator.php';

class AccessController extends ComponentController
{
    public function index()
    {
        if (!empty($_SESSION['loggedin'])) {
            header('Location: /');
        };

        $this->output('access','index');
    }

    public function login()
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }

        $validator = new UserValidator(new User($username, $password, $username));
        if ($validator->emailIsValid($username)) {
            $user = new User(null, $password, $username);
        } else {
            $user = new User($username, $password);
        }

        if ($user->login()) {
            if (empty($this->username)) {
                $_SESSION['username'] = $user->email;
            } else {
                $_SESSION['username'] = $user->username;
            }
            $_SESSION['role'] = $user->role;
            $_SESSION['loggedin'] = true;
            header('Location: /Campaign/');
        } else {
            $_SESSION['userDoesntExist'] = 'Der Benutzername und das Passwort stimmt nicht überein';
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

            $user = new User($username, $password, $email, $role);

            $validator = new UserValidator($user);
            $validator->isValid(sha1($reppassword));
            if (empty($validator->getErrorMessages())) {
                if ($user->insert()) {
                    $_SESSION['username'] = $user->username;
                    $_SESSION['role'] = $user->role;
                    $_SESSION['loggedin'] = true;
                    header('Location: /Campaign/');
                }
            }
            echo $validator->getErrorMessages();
        } else {
            header('Location: /Access/');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['loggedin'])) {
            $user = new User($_SESSION['username']);
            $user->logout();
        }
        header('Location: /');
    }
}