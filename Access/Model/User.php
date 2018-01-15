<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 10:24
 */


require_once 'Core/Model/Model.php';

class User extends Model
{
    protected $tableName = 'user';

    protected $id;
    protected $username;
    protected $password;
    protected $email;
    protected $role;

    public function __construct($id = null, $username, $password = null, $email = null, $role = null)
    {
        parent::getConnection();
        $this->id = $id;
        $this->username = $username;
        $this->password = sha1($password);
        $this->email = $email;
        $this->role = $role;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function insert()
    {
        $statement = $this->connection->prepare('INSERT INTO `' . $this->tableName . '` (`username`, `password`, `email`, `role`) VALUES (:username, :password, :email, :role)');

        $statement->bindParam(':username',$this->username);
        $statement->bindParam(':password',$this->password);
        $statement->bindParam(':email',$this->email);
        $statement->bindParam(':role',$this->role);

        if ($statement->execute()) {
            return true;
        }

    }

    public function login()
    {
        $statement = $this->connection->prepare('SELECT `username`, `role` FROM `' . $this->tableName . '` WHERE `username` = :username AND `password` = :password');
        $statement->bindParam(':username',$this->username);
        $statement->bindParam(':password',$this->password);

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $result = $statement->fetch();
            $this->role = $result['role'];

            return true;
        }
    }

    public function logout()
    {
        session_destroy();
    }
}