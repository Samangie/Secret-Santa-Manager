<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 08:28
 */

class AccessController extends ComponentController
{

    public function index() {}

    public function login() {

        if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $access = new Access();
            $access->login($username, sha1($password));
        }

    }

    public function register() {

        $mainController = new MainController();

        if (isset($_POST['register'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $reppassword = $_POST['reppasword'];
            $email = $_POST['email'];

            if ($password != $reppassword) {
                $_SESSION['notSamePassword'] == "Das Passwort stimmt nicht überein.";
                $mainController->main("access");

            }

            $user[] = array('username' => $username,
                          'password' => sha1($password),
                          'email' => $email,
                          'role' => "participant"
            );

            $access = new Access();

            if ($access->insert($user)){
                $mainController->main("campaign", "overview");
            }


        }else {
            $mainController->main("access");
        }

    }

}