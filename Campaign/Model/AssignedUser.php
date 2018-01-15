<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 15.12.2017
 * Time: 11:26
 */

require_once 'Core/Model/Model.php';

class AssignedUser extends Model
{
    protected $tableName = 'assigned_user';

    protected $campaignId;
    protected $santaId;
    protected $doneeId;

    public function __construct($campaign_id = 0, $santaId = 0, $doneeId = 0)
    {
        parent::getConnection();
        $this->campaignId = $campaign_id;
        $this->santaId = $santaId;
        $this->doneeId = $doneeId;
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
        $statement = $this->connection->prepare('INSERT INTO `' . $this->tableName . '` (`campaign_id`, `santa_id`, `donee_id`) VALUES (:campaign_id, :santa_id, :donee_id)');

        $statement->bindParam(':campaign_id',$this->campaignId);
        $statement->bindParam(':santa_id',$this->santaId);
        $statement->bindParam(':donee_id',$this->doneeId);

        if ($statement->execute()) {
            return true;
        }
    }
}