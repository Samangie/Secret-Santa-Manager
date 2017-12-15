<?php

require_once 'Core/Controller/MainController.php';

$mainController = new MainController();

$campaign = new Campaign();
$campaignEntries = $campaign->readAll();


$placeholders = array(
    array(
        "name" => "CAMPAIGNS",
        "template" => "allCampaigns_content",
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

$placeholderTemplate = file_get_contents('themes/secret-santa/campaign/test.html');

foreach ($placeholders as $placeholder) {
    $mainPlaceholder = $placeholder['name'];
    $templateName = $placeholder['template'];
    $loop = $placeholder['loop'];
    $innerPlaceholders = $placeholder['innerPlaceholders'];

    $contentTemplate;

    if ($loop) {
        $pathLoop = 'Campaign/lib/templates/' . $templateName . '_loop.html';
        $loopTemplate = file_get_contents($pathLoop);
        echo $loopTemplate;
        foreach ($placeholderContent[$mainPlaceholder] as $entry) {
            foreach ($innerPlaceholders as $innerPlaceholder) {
                $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', $entry[strtolower($innerPlaceholder)], $loopTemplate);
            }
            $contentTemplate = $loopTemplate;
        }
    }

    $placeholderTemplate = str_replace('[[' . $mainPlaceholder . ']]' ,$contentTemplate, $placeholderTemplate);
}

echo $placeholderTemplate;
