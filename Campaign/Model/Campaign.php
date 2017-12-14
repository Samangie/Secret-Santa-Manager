<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 10:26
 */

require_once "Core/Model/Model.php";

class Campaign extends Model
{
    protected $tableName = 'campaign';

    protected $title;
    protected $startdate;

    public function __construct($title = null, $startdate = null)
    {
        parent::getConnection();
        $this->title = $title;
        $this->startdate = $startdate;
    }

    public function insert()
    {
        $statement = $this->connection->prepare("INSERT INTO `" . $this->tableName . "` (`title`, `startdate`) VALUES (:title, :startdate)");

        $statement->bindParam(':title',$this->title);
        $statement->bindParam(':startdate',$this->startdate);

        if ($statement->execute()) {
            return true;
        }
    }

}