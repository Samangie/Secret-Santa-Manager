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

    public function readById(string $filter, int $id)
    {
        $statement = $this::getConnection()->prepare('SELECT '. $filter .' FROM `' . $this->tableName . '` WHERE id = :id');
        $statement->bindParam(':id',$id);

        $statement->execute();

        $result = $statement->fetch();
        return $result;
    }

    public function readByAttribut(string $filter, string $value)
    {
        $statement = $this::getConnection()->prepare('SELECT `' . $filter . '` FROM `' . $this->tableName . '` WHERE '. $filter .' = :'.$filter);
        $statement->bindParam(':'.$filter,$value);
        $statement->execute();

        if ($statement->rowCount() == 0) {
            return true;
        }
        return false;
    }

    public function deleteById(int $id)
    {
        $statement = $this::getConnection()->prepare('DELETE FROM `' . $this->tableName . '` WHERE id = :id');

        $statement->bindParam(':id',$id);

        if ($statement->execute()) {
            return true;
        }
        return false;
    }

}