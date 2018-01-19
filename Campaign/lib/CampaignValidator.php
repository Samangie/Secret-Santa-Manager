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
    public function __construct(Campaign $model)
    {
        parent::__construct($model);
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return false;
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function campaignIsAssigned(bool $setMessage = true)
    {
        $campaign = $this->model->readById('isassigned', $this->model->id);
        if (empty($campaign['isassigned'])) {
            return true;
        }
        if ($setMessage) {
            $errorMessage= 'Die Teilnehmer wurden bereits zugewiesen.';
            $this->setErrorMessages('campaign_is_assigned', $errorMessage);
        }
        return false;
    }

    public function userIsAssigned(bool $setMessage = true)
    {
        if (empty($this->model->readUserByCampaignId())) {
            return true;
        }
        if ($setMessage) {
            $errorMessage= 'Der Teilnehmer wurde bereits angemeldet oder die Teilnahme wurde geschlossen';
            $this->setErrorMessages('user_is_assigned', $errorMessage);
        }
        setcookie('user_is_already_assigned', '1');
        return false;
    }

    public function hasEnoughUsers(bool $setMessage = true)
    {
        $participantEntries = $this->model->getUsersIdsByCampaignId();
        if (sizeof($participantEntries) > 2) {
            return true;
        }
        if ($setMessage) {
            $errorMessage= 'Es haben sich noch nicht genug Teilnehmer angemeldet';
            $this->setErrorMessages('has_enough_users', $errorMessage);
        }
        return false;
    }

    public function userAdded(bool $setMessage = true)
    {
        if ($setMessage) {
            $errorMessage= 'Sie haben sich für die Kampanie angemeldet';
            $this->setErrorMessages('user_added', $errorMessage);
        }
    }

    public function assignmentIsAvailable(bool $setMessage = true)
    {
        if ($this->campaignIsAssigned() && $this->hasEnoughUsers()) {
            if ($setMessage) {
                $errorMessage= '<a href="/Campaign/assign?id=' . $this->model->id .'" class="alert-link"> Zuweisen </a>';
                $this->setErrorMessages('assignment_is_available', $errorMessage);
            }
            return true;
        }
        return false;
    }
}