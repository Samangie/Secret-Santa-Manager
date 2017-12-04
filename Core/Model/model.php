<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:15
 */

namespace SSM\Core\Model;


abstract class model
{
    abstract protected function insert();
    abstract protected function deleteById();
    abstract protected function read();

    protected  function getConnection() {
        $config = require_once 'config.php';
        $connection = new PDO('mysql:' . $config['database']['host'] . ';dbname=' . $config['database']['host'] . "'", $config['database']['user'], $config['database']['password']);
        return $connection;
    }
}