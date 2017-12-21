<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:15
 */

abstract class Model
{
    protected $tableName = null;
    protected $connection = null;

    abstract protected function insert();

    public function __construct()
    {
        $this->getConnection();
    }

    public function getConnection()
    {
        $config = require 'config.php';
        $this->connection = new PDO('mysql:host='. $config['database']['host'] .';dbname='. $config['database']['database'], $config['database']['username'], $config['database']['password'], array(PDO::ATTR_PERSISTENT => true));
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function readAll()
    {
        $statement = $this->connection->prepare('SELECT * FROM ' . $this->tableName);

        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
    }

    public function readById($id)
    {
        $statement = $this->connection->prepare('SELECT * FROM `' . $this->tableName . '` WHERE id = :id');
        $statement->bindParam(':id',$id);

        $statement->execute();

        $result = $statement->fetch();
        return $result;
    }

    public function readByAttribut($attribut, $value)
    {
        $statement = $this->connection->prepare('SELECT' . $attribut . 'FROM `' . $this->tableName . '` WHERE '. $attribut .'= :'.$attribut);
        $statement->bindParam(':'.$attribut,$value);

        $statement->execute();

        if ($statement->rowCount() == 0) {
            return true;
        }
    }

    public function deleteById($id)
    {
        $statement = $this->connection->prepare('DELETE FROM `' . $this->tableName . '` WHERE id = :id');

        $statement->bindParam(':id',$id);

        if ($statement->execute()) {
            return true;
        }

    }

}