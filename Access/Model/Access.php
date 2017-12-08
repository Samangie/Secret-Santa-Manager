<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 08.12.2017
 * Time: 10:24
 */
class Access extends Model
{
    protected $tableName = 'user';

    public function insert($data)
    {
        $statement = $this->connection->prepare("INSERT INTO $this->tableName (username, password, email, role) VALUES(?,?,?,?)");
        $statement->execute($data);
        echo "\nPDO::errorCode(): " . $statement->errorInfo();

    }

    public function login($username, $password)
    {
        $statement = $this->connection->prepare("SELECT username, role FROM $this->tableName WHERE username = ? AND password = ?");
        $statement->execute(array("username" => $username, "password" => $password));
        echo "\nPDO::errorCode(): " . $statement->errorInfo();

    }
}