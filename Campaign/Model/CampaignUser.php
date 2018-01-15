<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 11.12.2017
 * Time: 14:17
 */

require_once 'Core/Model/Model.php';

class CampaignUser extends Model
{
    protected $tableName = 'user_campaign';

    protected $username;
    protected $campaign_id;

    public function __construct($username = null, $campaign_id = null)
    {
        parent::getConnection();
        $this->username = $username;
        $this->campaign_id = $campaign_id;
    }

    public function insert()
    {
        $statement = $this->connection->prepare('INSERT INTO `' . $this->tableName . '` (`user_id`, `campaign_id`) VALUES ((SELECT id FROM user WHERE username = :username), :campaign_id)');

        $statement->bindParam(':username',$this->username);
        $statement->bindParam(':campaign_id',$this->campaign_id);

        if ($statement->execute()) {
            return true;
        }
    }

    public function readAllParticipantIds()
    {
        $statement = $this->connection->prepare('SELECT user_id FROM ' . $this->tableName . ' WHERE campaign_id = :campaign_id');

        $statement->bindParam(':campaign_id',$this->campaign_id);

        if ($statement->execute()) {
            $result = $statement->fetchAll();
            return $result;
        }
    }

    public function checkUserByCampId()
    {
        $statement = $this->connection->prepare('SELECT user_id FROM ' . $this->tableName . ' LEFT JOIN user ON ('.$this->tableName.'.user_id = user.id ) WHERE campaign_id = :campaign_id AND user.username = :username');

        $statement->bindParam(':campaign_id',$this->campaign_id);
        $statement->bindParam(':username',$this->username);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }
        return false;
    }
}