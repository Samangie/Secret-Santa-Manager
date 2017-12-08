<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

require_once "Access/Model/Access.php";

class AccessController extends ComponentController
{

    public function index() {}

    public function login() {

        $mainController = new MainController();

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $access = new Access();

            if ($access->login($username, sha1($password))){

                header("Location: /Campaign");
            }else {
                $_SESSION['userNotExists'] = "Der Benutzername und das Passwort stimmt nicht überein";

                header("Location: /");
            }
        }

    }

    public function register() {

        $mainController = new MainController();

        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppassword = $_POST['reppassword'];
            $email = $_POST['email'];

            if ($password != $reppassword) {
                $_SESSION['notSamePassword'] = "Das Passwort stimmt nicht überein.";
            }

            $user[] = array('username' => $username,
                          'password' => sha1($password),
                          'email' => $email,
                          'role' => 0,
            );

            $access = new Access();

            if ($access->insert($user)){
                //login($username, $password);
            }else {
                die();
            }

        }else {
            header("Location: /");
    }

    }

}