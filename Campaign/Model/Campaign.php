<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 10:26
 */

require_once "Core/Model/Model.php";
require_once "Campaign/Model/AssignedUser.php";

class Campaign extends Model
{
    protected $tableName = 'campaign';

    protected $id;
    protected $title;
    protected $startdate;
    protected $isAssigned;
    protected $users = array();

    public function __construct($id = null, $title = null, $startdate = null, $isAssigned = null)
    {
        parent::getConnection();
        $this->id = $id;
        $this->title = $title;
        $this->startdate = $startdate;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }

    public function insert()
    {
        $statement = $this->connection->prepare('INSERT INTO `' . $this->tableName . '` (`title`, `startdate`) VALUES (:title, :startdate)');

        $statement->bindParam(':title',$this->title);
        $statement->bindParam(':startdate',$this->startdate);

        if ($statement->execute()) {
            return true;
        }
    }

    public function assign()
    {
        $campaign = $this->readById('id', $this->id);
        $this->startdate = $campaign['startdate'];
        $currentDate = strtotime(date("Y-m-d"));

       /* if ($this->startdate != $currentDate) {
            return false;
        }*/

        $assignedUser = new AssignedUser($this->id);

        $campaignUser = new CampaignUser(null, $this->id);
        $allParticipants = $campaignUser->readAllParticipantIds();
        $allSantas = $allParticipants;
        $allDonees = $allParticipants;
        $assignedUserList = array();

        shuffle($allDonees);

        if (sizeof($allParticipants) <= 1) {
            return false;
        }

        do {
            //Find key from first element
            $keySanta = array_search(reset($allSantas), $allSantas);
            $keyDonee = array_search(reset($allDonees), $allDonees);

            if (sizeof($allSantas) == 2) {
                if (reset($allSantas) == reset($allDonees) || $allSantas[$keySanta+1] == $allDonees[$keyDonee+1]){
                    $keyDonee += 1;
                }
            }
            if ($allSantas[$keySanta] == $allDonees[$keyDonee]) {
                array_push($allDonees, $allDonees[$keyDonee]);
                unset($allDonees[$keyDonee]);
                $allDonees = array_values($allDonees);
            } else {
                $santa = $allSantas[$keySanta];
                $donee = $allDonees[$keyDonee];

                unset($allSantas[$keySanta]);
                unset($allDonees[$keyDonee]);

                $secretSantaPair = array('santa' => $santa, 'donee' => $donee);
                array_push($assignedUserList, $secretSantaPair);

                $allSantas = array_values($allSantas);
                $allDonees = array_values($allDonees);
            }
        } while (sizeof($allSantas));

        foreach ($assignedUserList as $pair) {
            $assignedUser->santaId = $pair['santa']['user_id'];
            $assignedUser->doneeId = $pair['donee']['user_id'];
            $assignedUser->insert();
        }

        $this->isAssigned = 1;
        $this->updateAttrAssigned();

    }

    public function updateAttrAssigned()
    {
        $statement = $this->connection->prepare('UPDATE `' . $this->tableName . '` SET isAssigned = :isAssigned WHERE id = :id');

        $statement->bindParam(':id',$this->id);
        $statement->bindParam(':isAssigned',$this->isAssigned);

        if ($statement->execute()) {
            return true;
        }
    }

    public function getUsersByCampaign()
    {
        $statement = $this->connection->prepare('SELECT `user_campaign`.`user_id`, `user`.`username` FROM `user_campaign` 
                                                 LEFT JOIN user ON (`user_campaign`.`user_id` = `user`.`id` ) 
                                                 WHERE `campaign_id` = :campaign_id');

        $statement->bindParam(':campaign_id',$this->id);
        $statement->execute();
        $results = $statement->fetchAll();

        foreach ($results as $result) {
            array_push($this->users, new User($result['user_id'], $result['username']));
        }
        return $this->users;
    }
}