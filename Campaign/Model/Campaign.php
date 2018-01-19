<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 10:26
 */

require_once "Core/Model/Model.php";
require_once "Campaign/lib/CampaignMail.php";

class Campaign extends Model
{
    protected $tableName = 'campaign';

    protected $id;
    protected $title;
    protected $startdate;
    protected $isAssigned;
    protected $users = array();

    public function __construct(int $id = 0, string $title = '', string $startdate = null, int $isAssigned = 0)
    {
        $this->id = $id;
        $this->title = $title;
        $this->startdate = $startdate;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }

    public function setUser(User $user)
    {
        array_push($this->users,$user);
    }

    public function insert()
    {
        $databaseDate = date("Y-m-d", strtotime($this->startdate));
        $statement = $this::getConnection()->prepare('INSERT INTO `' . $this->tableName . '` (`title`, `startdate`) VALUES (:title, :startdate)');

        $statement->bindParam(':title',$this->title);
        $statement->bindParam(':startdate',$databaseDate);

        if ($statement->execute()) {
            return true;
        }
    }

    public function readAllCampaigns()
    {
        $statement = $this::getConnection()->prepare('SELECT `id`, `title`, `startdate`, `isassigned` FROM ' . $this->tableName);

        $statement->execute();

        $results = $statement->fetchAll();
        foreach ($results as $key => $field) {
            $results[$key]['startdate'] = date("d.m.Y", strtotime($results[$key]['startdate']));
        }
        return $results;
    }

    public function assign()
    {
        $campaign = $this->readById('id, startdate', $this->id);
        $this->startdate = $campaign['startdate'];
        $currentDate = strtotime(date("Y-m-d"));

       /* if ($this->startdate != $currentDate) {
            return false;
        }*/
        $allParticipants = $this->getUsersByCampaignId();
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

        $mail = new CampaignMail();

        foreach ($assignedUserList as $pair) {

            $santaId = $pair['santa']->getId();
            $doneeId = $pair['donee']->getId();
            $santaEmail = $pair['santa']->getEmail();
            $santaName = $pair['santa']->getUsername();
            $doneeName = $pair['donee']->getUsername();
            $mail->sendAssignmentMail($santaEmail, $santaName, $doneeName);
            //$this->insertAssignedUserPair($santaId, $doneeId);
        }

        //$this->isAssigned = 1;
        //$this->updateAttrAssigned();
    }

    public function updateAttrAssigned()
    {
        $statement = $this::getConnection()->prepare('UPDATE `' . $this->tableName . '` SET isAssigned = :isAssigned WHERE id = :id');

        $statement->bindParam(':id',$this->id);
        $statement->bindParam(':isAssigned',$this->isAssigned);

        if ($statement->execute()) {
            return true;
        }
    }

    public function getUsersIdsByCampaignId()
    {
        $statement = $this::getConnection()->prepare('SELECT `user_id` FROM `user_campaign` WHERE `campaign_id` = :campaign_id' );

        $statement->bindParam(':campaign_id',$this->id);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getUsersByCampaignId()
    {
        $statement = $this::getConnection()->prepare('SELECT `user_campaign`.`user_id`, `user`.`username`, `user`.`email` FROM `user_campaign` 
                                                 LEFT JOIN `user` ON (`user_campaign`.`user_id` = `user`.`id` ) 
                                                 WHERE `campaign_id` = :campaign_id');

        $statement->bindParam(':campaign_id',$this->id);
        $statement->execute();
        $results = $statement->fetchAll();

        foreach ($results as $result) {
            array_push($this->users, new User($result['user_id'], $result['username'], '' ,$result['email']));
        }
        return $this->users;
    }

    public function addUserToCampaign()
    {
        foreach ($this->users as $user) {
            $username = $user->getUsername();
            $statement = $this::getConnection()->prepare('INSERT INTO `user_campaign` (`user_id`, `campaign_id`) VALUES ((SELECT `id` FROM `user` WHERE `username` = :username), :campaign_id)');

            $statement->bindParam(':username',$username);
            $statement->bindParam(':campaign_id',$this->id);

            if ($statement->execute()) {
                return true;
            }
        }
    }

    public function readUserByCampaignId()
    {
        foreach ($this->users as $user) {
            $username = $user->getUsername();
            $statement = $this::getConnection()->prepare('SELECT `user_campaign`.`user_id` FROM `user_campaign` 
                                                 LEFT JOIN user ON (`user_campaign`.`user_id` = `user`.`id` ) 
                                                 WHERE `campaign_id` = :campaign_id AND `user`.`username` = :username');

            $statement->bindParam(':campaign_id', $this->id);
            $statement->bindParam(':username', $username);

            $statement->execute();

            if ($statement->execute()) {
                $result = $statement->fetchAll();
                return $result;
            }
        }
    }

    public function insertAssignedUserPair($santaId, $doneeId)
    {
        $statement = $this::getConnection()->prepare('INSERT INTO `assigned_user` (`campaign_id`, `santa_id`, `donee_id`) VALUES (:campaign_id, :santa_id, :donee_id)');

        $statement->bindParam(':campaign_id',$this->id);
        $statement->bindParam(':santa_id',$santaId);
        $statement->bindParam(':donee_id',$doneeId);

        $statement->execute();
    }
}