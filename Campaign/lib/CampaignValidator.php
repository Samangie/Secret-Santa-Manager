<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 15.12.2017
 * Time: 14:58
 */

require_once 'Core/lib/Validator.php';

class CampaignValidator extends Validator
{
    public $errorMessages;

    public function __construct($model, $additionalProperty = null)
    {
        $this->model = $model;
        $this->additionalProperty = $additionalProperty;
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

    public function campaignIsAssigned()
    {
        $campaign = $this->model->readById($this->model->id);
        if (empty($campaign['isassigned'])) {
            return true;
        }
        $this->errorMessages .= 'Die Teilnehmer wurden bereits zugewiesen. <br/>';
        return false;
    }

    public function userIsAssigned()
    {
        if ($this->model->checkUserByCampId()) {
            $this->errorMessages  .= 'Der Teilnehmer wurde bereits angemeldet oder die Teilnahme wurde geschlossen';
            return false;
        }
        return true;
    }

    public function hasEnoughUsers() {
        $campaignUser = new CampaignUser(null, $this->model->id);
        $participantEntries = $campaignUser->readAllParticipant();

        if (sizeof($participantEntries) > 1) {
            return true;
        }
        $this->errorMessages .= 'Es haben sich noch keine Teilnehmer angemeldet <br/>';
        return false;

    }

}