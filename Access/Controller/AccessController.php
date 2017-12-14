<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

require_once "Access/Model/User.php";
require_once "Access/AccessValidator.php";

class AccessController extends ComponentController
{

    public function index() {

        if(isset($_SESSION['loggedin']) && !empty($_SESSION['loggedin'])) {
            header("Location: /");
        };

        $this->output("access","index");

    }

    public function login($username = null, $password = null) {

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
        }

        $validation = new AccessValidator();

        if($validation->valueIsString($username) & $validation->valueIsString($password)) {
            $access = new User();

            if ($access->login($username, sha1($password))){
                $_SESSION['username'] = $username;
                $_SESSION['loggedin'] = true;
                header("Location: /Campaign/");
            }else {
                $_SESSION['userDoesntExist'] = "Der Benutzername und das Passwort stimmt nicht überein";

            }
        }
        header("Location: /Access/");
    }

    public function register() {

        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppassword = $_POST['reppassword'];
            $email = $_POST['email'];

            $validation = new AccessValidator();

            if($validation->valueIsString($username) & $validation->uniqueUsername($username) &
                $validation->valueIsString($password) & $validation->passwordIsValid($password) &
                $validation->comparePasswords($password, $reppassword) & $validation->emailIsValid($email) &
                $validation->uniqueEmail($email) & $validation->valueIsString($email)) {

                $user[] = array('username' => $username,
                    'password' => sha1($password),
                    'email' => $email,
                    'role' => 0,
                );

                $access = new User();

                if ($access->insert($user)) {
                    $_SESSION['username'] = $username;
                    $_SESSION['loggedin'] = true;
                    header("Location: /Campaign/");
                }

            }
        }else {
            header("Location: /Access/");
        }

    }

    public function logout()
    {
        session_destroy();
        header("Location: /");
    }
}