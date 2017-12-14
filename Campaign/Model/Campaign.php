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

    public function insert($data)
    {
        foreach($data as $entry) {
            $title = (string)$entry['title'];
            $startdate = (string)$entry['startdate'];
        }

        $statement = $this->connection->prepare("INSERT INTO `" . $this->tableName . "` (`title`, `startdate`) VALUES (:title, :startdate)");

        $statement->bindParam(':title',$title);
        $statement->bindParam(':startdate',$startdate);

        if($statement->execute()) {
            return true;
        }
    }

}