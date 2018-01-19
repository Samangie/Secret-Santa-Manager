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

    public function __construct($id = 0, $username, $password = '', $email = '', $role = 0)
    {
        parent::getConnection();
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function insert()
    {
        $statement = $this::getConnection()->prepare('INSERT INTO `' . $this->tableName . '` (`username`, `password`, `email`, `role`) VALUES (:username, :password, :email, :role)');

        $statement->bindParam(':username',$this->username);
        $statement->bindParam(':password',sha1($this->password));
        $statement->bindParam(':email',$this->email);
        $statement->bindParam(':role',$this->role);

        if ($statement->execute()) {
            return true;
        }

    }

    public function login()
    {
        if (empty($this->username)) {
            $statement = $this::getConnection()->prepare('SELECT `username`, `role` FROM `' . $this->tableName . '` WHERE `email` = :email AND `password` = :password');
            $statement->bindParam(':email',$this->email);
        } else {
            $statement =$this::getConnection()->prepare('SELECT `username`, `role` FROM `' . $this->tableName . '` WHERE `username` = :username AND `password` = :password');
            $statement->bindParam(':username',$this->username);
        }
        $statement->bindParam(':password',sha1($this->password));

        $statement->execute();

        if ($statement->rowCount() == 1) {
            $result = $statement->fetch();
            $this->role = $result['role'];
            $this->username = $result['username'];
            return true;
        }
    }

    public function logout()
    {
        session_destroy();
    }

    public function getCampaignIdsByUsername()
    {
        $statement = $this::getConnection()->prepare('SELECT campaign_id FROM user_campaign WHERE user_id =  (SELECT id FROM user WHERE username = :username);' );

        $statement->bindParam(':username',$this->username);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }
}