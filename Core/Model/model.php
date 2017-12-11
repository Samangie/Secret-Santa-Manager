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

    abstract protected function insert($data);

    public function getConnection() {

        $config = require_once 'config.php';
        $connection = new PDO("mysql:host=". $config['database']['host'] .";dbname=". $config['database']['database'], $config['database']['username'], $config['database']['password']);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;

    }

    public function readAll(){
        $statement = $this->getConnection()->prepare("SELECT * FROM " . $this->tableName);

        $statement->execute();

        $result = $statement->fetchAll();
        return $result;
    }

}