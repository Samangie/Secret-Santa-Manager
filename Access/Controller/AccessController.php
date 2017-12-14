<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

require_once "Access/Model/User.php";
require_once "Access/lib/UserValidator.php";

class AccessController extends ComponentController
{
    public function index()
    {
        if (!empty($_SESSION['loggedin'])) {
            header("Location: /");
        };

        $this->output("access","index");
    }

    public function login()
    {
        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }

        $user = new User($username, $password);

        if ($user->login()) {
            $_SESSION['username'] = $user->username;
            $_SESSION['loggedin'] = true;
            header("Location: /Campaign/");
        } else {
            $_SESSION['userDoesntExist'] = "Der Benutzername und das Passwort stimmt nicht überein";
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
            $role = 0;

            $user = new User($username, $password, $email, $role);

            $validator = new UserValidator();

            if($validator->isValid($user, sha1($reppassword))) {
                if ($user->insert()) {
                    $_SESSION['username'] = $user->username;
                    $_SESSION['loggedin'] = true;
                    header("Location: /Campaign/");
                }
            }
            header("Location: /Access/");
        } else {
            header("Location: /Access/");
        }
    }

    public function logout()
    {
        if (!empty($_SESSION['username'])) {
            $user = new User($_SESSION['username']);
            $user->logout();
        }
        header("Location: /");
    }
}