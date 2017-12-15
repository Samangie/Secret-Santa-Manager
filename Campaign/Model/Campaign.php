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

    public function __construct($id = null, $title = null, $startdate = null)
    {
        parent::getConnection();
        $this->id = $id;
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

    public function assign()
    {
        $campaign = $this->readById('id', $this->id);
        $this->startdate = $campaign['startdate'];
        $currentDate = strtotime(date("Y-m-d"));

       /* if ($this->startdate != $currentDate) {
            return false;
        }*/
        $assignedUser = new AssignedUser($this->id);
        if(!$assignedUser->readByAttribut('campaign_id', $this->id)) {
            $_SESSION['assignedAlready']   = "Die Teilnehmer wurden bereits zugewiesen.";
            return false;
        }

        $campaignUser = new CampaignUser(null, $this->id);
        $allParticipants = $campaignUser->readAllParticipantIds();
        $allSantas = $allParticipants;
        $allDonees = $allParticipants;
        $assignedUserList = array();

        shuffle($allDonees);

        if(sizeof($allParticipants) <= 1) {
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
            echo var_dump($allSantas);
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
        }while(sizeof($allSantas));

        foreach($assignedUserList as $pair) {
            $assignedUser->santaId = $pair['santa']['user_id'];
            $assignedUser->doneeId = $pair['donee']['user_id'];
            $assignedUser->insert();
        }

    }

}