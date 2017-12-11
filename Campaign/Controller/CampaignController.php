<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:59
 */

include 'Core/Controller/ComponentController.php';
require_once "Campaign/Model/Campaign.php";

class CampaignController extends ComponentController
{
    public function index()
    {
        $campaign = new Campaign();
        $camaignEntries = $campaign->readAll();

        $this->output("campaign", "index", $camaignEntries);

    }

}