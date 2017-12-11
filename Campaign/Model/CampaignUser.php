<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 14:17
 */

require_once "Core/Model/Model.php";

class CampaignUser extends Model
{
    protected $tableName = 'user_campaign';

    public function insert($data)
    {
        foreach($data as $entry) {
            $username = (string)$entry['username'];
            $campaign_id = (string)$entry['campaign_id'];
        }

        $statement = $this->getConnection()->prepare("INSERT INTO `" . $this->tableName . "` (`user_id`, `campaign_id`) VALUES ((SELECT id FROM user WHERE username = :username), :campaign_id)");

        $statement->bindParam(':username',$username);
        $statement->bindParam(':campaign_id',$campaign_id);

        if($statement->execute()) {
            return true;
        }
    }
}