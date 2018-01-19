<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 19.01.2018
 * Time: 16:28
 */

require_once 'Core/lib/Mail.php';

class CampaignMail extends Mail
{
    public function sendAssignmentMail(string $santaEmail, string $santaName, string $doneeName)
    {
        $placeholders = array (
            new Placeholder('SANTA_NAME', '', '', array(),
                array(
                    'santa_name' => $santaName
                )
            ),
            new Placeholder('DONEE_NAME', '', '', array(),
                array(
                    'donee_name' => $doneeName
                )
            ),
        );
        $path = 'Campaign/lib/templates/mail_template/assignmentMail.html';
        $message = file_get_contents($path);

        foreach ($placeholders as $placeholder) {
            $message = $placeholder->fillContent($message, 'Campaign');
        }
        $subject = 'Wichtelauslosung';

        $this->sendMail($santaEmail, $subject, $message);
    }

}