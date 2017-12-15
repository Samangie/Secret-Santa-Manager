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
    public function __construct()
    {
        if (isset($_SESSION['loggedin'])) {
            $campaign = new Campaign();
            $camaignEntries = $campaign->readAll();

            $this->output("campaign", "index", $camaignEntries);
        } else {
            header("Location: /Access/");
        }
    }

    public function create()
    {
        if (isset($_POST['create-campaign']) && !empty($_SESSION['role'])) {

            $title = $_POST['title'];
            $startdate = $_POST['startdate'];

            $campaign = new Campaign(null, $title, $startdate);

            if ($campaign->insert()) {
                header("Location: /Campaign/");
            }
        } else {
            header("Location: /");
        }
    }

    public function delete()
    {
        if (isset($_GET['id']) && !empty($_SESSION['role'])) {

            $id = $_GET['id'];

            $campaign = new Campaign();
            $campaign->deleteById($id);

            header("Location: /Campaign/");
        } else {
            header("Location: /Campaign/");
        }
    }

    public function addParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];
            $username = $_SESSION['username'];

            $campaignUser = new CampaignUser($username,$campaign_id);

            $campaignUser->insert();

            header("Location: /Campaign/");
        }
    }

    public function showParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];

            $campaignUser = new CampaignUser(null, $campaign_id);

            $participantEntries = $campaignUser->readAllParticipant();

            $this->output("campaign", "participants", $participantEntries);
        }
    }

    public function assign()
    {
        if (isset($_GET['id']) && !empty($_SESSION['role'])) {
            $campaign_id = $_GET['id'];
            $campaign = new Campaign($campaign_id);
            if($campaign->assign()) {
                header("Location: /Campaign/");
            }
        }
        header("Location: /");
    }

}