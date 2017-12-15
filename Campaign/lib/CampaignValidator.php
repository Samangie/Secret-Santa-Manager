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
    public function isValid($campaign, $additionalProperty = null)
    {
        $this->model = $campaign;
    }

    public function campaignIsAssigned()
    {
        $campaign = $this->model->readById($this->model->id);
        if (!empty($campaign['isAssigned'])) {
            return true;
        }

        $_SESSION['assignedAlready']   = "Die Teilnehmer wurden bereits zugewiesen.";
        return false;
    }

    public function userIsAssigned()
    {
        if ($this->model->checkUserByCampId()) {
            $_SESSION['userIsAssigned']   = "Der Teilnehmer wurde bereits angemeldet oder die Teilnahme wurde geschlossen";
            return false;
        }
        return true;
    }

}