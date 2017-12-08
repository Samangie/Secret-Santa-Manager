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

    function __construct()
    {
        $this->connection = $this->getConnection();
    }

    protected  function getConnection() {
        $config = require_once 'config.php';
        $connection = new PDO('mysql:' . $config['database']['host'] . ';dbname=' . $config['database']['host'] . "'", $config['database']['user'], $config['database']['password']);
        return $connection;
    }

}