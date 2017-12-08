<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 10:24
 */


require_once "Core/Model/Model.php";

class Access extends Model
{
    protected $tableName = 'user';

    public function insert($data)
    {
        foreach($data as $d) {
            $username = (string)$d['username'];
            $password = (string)$d['password'];
            $email = (string)$d['email'];
            $role = (int)$d['role'];
        }

        $statement = $this->getConnection()->prepare("INSERT INTO " . $this->tableName . " (username, password, email, role) VALUES (:username, :password, :email, :role)");

        $statement->bindParam(':username',$username);
        $statement->bindParam(':password',$password);
        $statement->bindParam(':email',$email);
        $statement->bindParam(':role',$role);

        $statement->execute();

    }

    public function login($username, $password)
    {
        $statement = $this->getConnection()->prepare("SELECT `username`, `role` FROM `" . $this->tableName . "` WHERE `username` = :username AND `password` = :password");
        $statement->bindParam(':username',$username);
        $statement->bindParam(':password',$password);

        if($statement->execute()){
            return true;
        }
    }
}