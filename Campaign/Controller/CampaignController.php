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

            if (empty($_SESSION['role'])) {
                $hasNoRights = true;
            } else {
                $hasNoRights = false;
            }

            $placeholders = array(
                array(
                    'name' => 'CAMPAIGNS_CREATE',
                    'template' => 'campaigns_create_form',
                    'type' => false,
                    'innerPlaceholders' => '',
                    'placeholderContent' => '',
                ),
                array(
                    'name' => 'CAMPAIGNS',
                    'template' => 'allCampaigns_content_loop',
                    'type' => 'loop',
                    'innerPlaceholders' =>
                        array(
                            'ID',
                            'TITLE',
                            'STARTDATE',
                            'ISASSIGNED'
                        ),
                    'placeholderContent' => $campaignEntries,
                ),
                array(
                    'name' => 'RIGHTS',
                    'template' => '',
                    'type' => 'area',
                    'innerPlaceholders' => '',
                    'placeholderContent' => array(
                        'isTrue' => $hasNoRights,
                        'replace' => ''
                    ),
                ),
            );

            $this->output('campaign', 'index', $placeholders);
        } else {
            header('Location: /Access/');
        }
    }

    public function create()
    {
        if (isset($_POST['create-campaign']) && !empty($_SESSION['role'])) {

            $title = $_POST['title'];
            $startdate = $_POST['startdate'];

            $campaign = new Campaign(null, $title, $startdate);

            if ($campaign->insert()) {
                header('Location: /Campaign/');
            }
        } else {
            header('Location: /');
        }
    }

    public function delete()
    {
        if (isset($_GET['id']) && !empty($_SESSION['role'])) {

            $id = $_GET['id'];

            $campaign = new Campaign();
            $campaign->deleteById($id);

            header('Location: /Campaign/');
        } else {
            header('Location: /Campaign/');
        }
    }

    public function addParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];
            $username = $_SESSION['username'];

            $campaignUser = new CampaignUser($username,$campaign_id);

            $validator = new CampaignValidator($campaignUser);
            $validator->userIsAssigned();
            $campaign = new Campaign($campaign_id);
            $validatorCampaign = new CampaignValidator($campaign);
            $validatorCampaign->campaignIsAssigned();
            if (empty($validator->getErrorMessages()) && empty($validatorCampaign->getErrorMessages())) {
                $campaignUser->insert();
                $_SESSION['userAdded'] = 'Sie haben sich für die Kampanie angemeldet';
                header('Location: /Campaign/');
            }
        }
        header('Location: /Campaign/');
    }

    public function showParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];

            $campaignUser = new CampaignUser(null, $campaign_id);
            $participantEntries = $campaignUser->readAllParticipant();

            $campaign = new Campaign($campaign_id);

            $validator = new CampaignValidator($campaign);
            $validator->campaignIsAssigned();
            $validator->hasEnoughUsers();
            if (empty($validator->getErrorMessages())) {
                $assignLink = '<a href="/Campaign/assign?id=' . $campaign_id .'" class="btn btn-primary"> Zuweisen </a><br/><br/>';
            } else {
                $assignLink = $validator->getErrorMessages();
            }

            if (empty($_SESSION['role'])) {
                $hasNoRights = true;
            } else {
                $hasNoRights = false;
            }

            $placeholders = array(
                array(
                    'name' => 'PARTICIPANTS',
                    'template' => 'allParticipants_content_loop',
                    'type' => 'loop',
                    'innerPlaceholders' =>
                        array(
                            'USERNAME'
                        ),
                    'placeholderContent' => $participantEntries
                ),
                array(
                    'name' => 'ASSIGNED',
                    'template' => '',
                    'type' => false,
                    'innerPlaceholders' => '',
                    'placeholderContent' => $assignLink
                ),
                array(
                    'name' => 'RIGHTS',
                    'template' => '',
                    'type' => 'area',
                    'innerPlaceholders' => '',
                    'placeholderContent' => array(
                        'isTrue' => $hasNoRights,
                        'replace' => ''
                    ),
                ),
            );


            $this->output('campaign', 'participants',$placeholders);
        }
    }

    public function assign()
    {
        if (isset($_GET['id']) && !empty($_SESSION['role'])) {
            $campaign_id = $_GET['id'];
            $campaign = new Campaign($campaign_id);

            $validator = new CampaignValidator($campaign);
            $validator->campaignIsAssigned();
            if (empty($validator->getErrorMessages())) {
                $campaign->assign();
                header('Location: /Campaign/');
            }
            die();
            header('Location: /Campaign/showParticipant?id=' . $_GET['id']);
        }
        header('Location: /Campaign/');
    }

}