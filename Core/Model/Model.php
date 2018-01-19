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
    private $connection = null;

    abstract protected function insert();

    public function getConnection()
    {
        $config = require 'config.php';
        if (!$this->connection) {
            $this->connection = new \PDO('mysql:host='. $config['database']['host'] .';dbname='. $config['database']['database'], $config['database']['username'], $config['database']['password']);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->connection;
    }

    public function readAll()
    {
        $statement = $this::getConnection()->prepare('SELECT * FROM ' . $this->tableName);

        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
    }

    public function readById(int $id)
    {
        $statement = $this::getConnection()->prepare('SELECT * FROM `' . $this->tableName . '` WHERE id = :id');
        $statement->bindParam(':id',$id);

        $statement->execute();

        $result = $statement->fetch();
        return $result;
    }

    public function readByAttribut($attribut, $value)
    {
        $statement = $this::getConnection()->prepare('SELECT ' . $attribut . ' FROM `' . $this->tableName . '` WHERE '. $attribut .'= :'.$attribut);
        $statement->bindParam(':'.$attribut,$value);

        $statement->execute();

        if ($statement->rowCount() == 0) {
            return true;
        }
    }

    public function deleteById(int $id)
    {
        $statement = $this::getConnection()->prepare('DELETE FROM `' . $this->tableName . '` WHERE id = :id');

        $statement->bindParam(':id',$id);

        if ($statement->execute()) {
            return true;
        }

    }

}