<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 15:59
 */

include_once 'Core/Controller/ComponentController.php';
require_once 'Campaign/Model/Campaign.php';
require_once 'Campaign/lib/CampaignValidator.php';

class CampaignController extends ComponentController
{
    public function index()
    {
        if (isset($_SESSION['loggedin'])) {
            $campaign = new Campaign();
            $campaignEntries = $campaign->readAllCampaigns();
            
            if (empty($_SESSION['role'])) {
                $hasNoRights = 1;
            } else {
                $hasNoRights = 0;
            }
            $user = new User(null, $_SESSION['username']);

            $campaignsUserAssigned = $user->getCampaignIdsByUsername();

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
                array(
                    'name' => 'CAMPAIGNS_USER_ASSIGNED',
                    'template' => 'user_campaigns_content',
                    'type' => 'loop',
                    'innerPlaceholders' => array(
                        'CAMPAIGN_ID'
                    ),
                    'placeholderContent' => $campaignsUserAssigned,
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

            $campaign = new Campaign(0, $title, $startdate);

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

            $campaign = new Campaign($campaign_id);
            $user = new User(0, $username);
            $campaign->setUser($user);
            $_SESSION['errorMessages'] = array();

            $validator = new CampaignValidator($campaign);
            $validator->userIsAssigned();
            if (empty($_SESSION['errorMessages'])) {
                $campaign->addUserToCampaign();
                $validator->userAdded();
                setcookie('user_is_assigned', '1');
                header('Location: /Campaign/');
            }
        }
        header('Location: /Campaign/');
    }

    public function showParticipant()
    {
        if (isset($_GET['id']) && !empty($_SESSION['loggedin'])) {

            $campaign_id = $_GET['id'];

            $campaign = new Campaign($campaign_id);
            $participantEntries = $campaign->getUsersByCampaignId();

            $_SESSION['errorMessages'] = array();

            $validator = new CampaignValidator($campaign);
            $validator->assignmentIsAvailable();

            $errorMessages = $_SESSION['errorMessages'];

            if (empty($_SESSION['role'])) {
                $hasNoRights = true;
            } else {
                $hasNoRights = false;
            }

            $placeholders = array(
                array(
                    'name' => 'PARTICIPANTS',
                    'template' => 'allParticipants_content_loop',
                    'type' => 'object-loop',
                    'innerPlaceholders' =>
                        array(
                            'USERNAME'
                        ),
                    'placeholderContent' => $participantEntries
                ),
                array(
                    'name' => 'ERROR_LOGIN',
                    'template' => 'errorMessagesAssignment_content',
                    'type' => false,
                    'innerPlaceholders' =>
                        array(
                            'HAS_ENOUGH_USERS',
                            'ASSIGNMENT_IS_AVAILABLE',
                            'CAMPAIGN_IS_ASSIGNED'
                        ),
                    'placeholderContent' => $errorMessages,
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
            if ($validator->campaignIsAssigned()) {
                $campaign->assign();
                header('Location: /Campaign/');
            }
            header('Location: /Campaign/showParticipant?id=' . $_GET['id']);
        }
        header('Location: /Campaign/');
    }

}