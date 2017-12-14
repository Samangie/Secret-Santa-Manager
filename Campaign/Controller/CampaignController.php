<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:59
 */

include_once 'Core/Controller/ComponentController.php';
require_once "Campaign/Model/Campaign.php";
require_once "Campaign/Model/CampaignUser.php";

class CampaignController extends ComponentController
{
    public function index()
    {
        $campaign = new Campaign();
        $camaignEntries = $campaign->readAll();

        $this->output("campaign", "index", $camaignEntries);
    }

    public function create()
    {
        if (isset($_POST['create-campaign'])) {

            $title = $_POST['title'];
            $startdate = $_POST['startdate'];

            $campaign = new Campaign();

            $campaignEntry[] = array('title' => $title,
                'startdate' => $startdate,
            );

            if ($campaign->insert($campaignEntry)) {
                header("Location: /Campaign/");
            }
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $campaign = new Campaign();
            $campaign->deleteById($id);

            header("Location: /Campaign/");
        }
    }

    public function addParticipant()
    {
        if (isset($_GET['id'])) {

            $campaign_id = $_GET['id'];
            $username = $_SESSION['username'];

            $campaignUserEntry[] = array('campaign_id' => $campaign_id,
                'username' => $username,
            );

            $campaignUser = new CampaignUser();

            $campaignUser->insert($campaignUserEntry);

            header("Location: /Campaign/");
        }
    }

    public function showParticipant()
    {
        if (isset($_GET['id'])) {

            $campaign_id = $_GET['id'];

            $campaignUser = new CampaignUser();

            $participantEntries = $campaignUser->readAllParticipant($campaign_id);

            $this->output("campaign", "participants", $participantEntries);
        }
    }

}