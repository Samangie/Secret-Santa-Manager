<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:59
 */

include_once 'Core/Controller/ComponentController.php';
require_once 'Campaign/Model/Campaign.php';
require_once 'Campaign/Model/CampaignUser.php';
require_once 'Campaign/lib/CampaignValidator.php';

class CampaignController extends ComponentController
{
    public function index()
    {
        if (isset($_SESSION['loggedin'])) {
            $campaign = new Campaign();
            $campaignEntries = $campaign->readAll();

            $placeholders = array(
                array(
                    "name" => "CAMPAIGNS",
                    "template" => "allCampaigns_content_loop",
                    "loop" => true,
                    "innerPlaceholders" =>
                        array(
                            "ID",
                            "TITLE",
                            "STARTDATE",
                            "ISASSIGNED"
                        ),
                )
            );

            $placeholderContent = array(
                "CAMPAIGNS" =>  $campaignEntries,
            );

            $this->output("campaign", "index", $placeholders, $placeholderContent);
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

            $validator = new CampaignValidator();
            $validator->isValid($campaignUser);
            if ($validator->userIsAssigned()) {
                $campaignUser->insert();
                header("Location: /Campaign/");
            }
        }
    }

    public function showParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];

            $campaignUser = new CampaignUser(null, $campaign_id);
            $participantEntries = $campaignUser->readAllParticipant();

            $campaign = new Campaign();
            $campaignEntry = $campaign->readById($campaign_id);

            if (empty($campaignEntry['isassigned']) && !empty($participantEntries)) {
                $assignLink = "<a href='/Campaign/assign?id=" . $campaign_id ."' > Zuweisen </a>";
            } else if ($participantEntries > 1) {
                $assignLink = "Noch zu wenige Teilnehmer vorhanden";
            } else {
                $assignLink = "Wurde bereits zugewiesen";
            }
            $placeholders = array(
                array(
                    "name" => "PARTICIPANTS",
                    "template" => "allParticipants_content_loop",
                    "loop" => true,
                    "innerPlaceholders" =>
                        array(
                            "USERNAME"
                        ),
                ),
                array(
                    'name' => 'ASSIGNED',
                    'template' => '',
                    'loop' => false,
                    'innerPlaceholders' => ''
                )
            );

            $placeholderContent = array(
                'PARTICIPANTS' =>  $participantEntries,
                'ASSIGNED' => $assignLink,
            );


            $this->output("campaign", "participants",$placeholders, $placeholderContent);
        }
    }

    public function assign()
    {
        if (isset($_GET['id']) && !empty($_SESSION['role'])) {
            $campaign_id = $_GET['id'];
            $campaign = new Campaign($campaign_id);

            $validator = new CampaignValidator();
            $validator->isValid($campaign);
            if (!$validator->campaignIsAssigned()) {
                $campaign->assign();
                header("Location: /Campaign/");
            }
            header("Location: /Campaign/showParticipant?id=" . $_GET['id']);
        }
        header("Location: /Campaign/");
    }

}