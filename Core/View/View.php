<?php
/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 30.11.2017
 * Time: 13:41
 */


class View
{
    protected $controllerName;
    protected $methodName;
    protected $placeholders;
    protected $placeholderContent;

    public function __construct($controllerName,$methodName, $placeholders, $placeholderContent)
    {
        $this->controllerName = $controllerName;
        $this->methodName = $methodName;
        $this->placeholders = $placeholders;
        $this->placeholderContent = $placeholderContent;

        $this->display();
    }

    private function display()
    {
        $config = require 'config.php';
        require_once 'themes/' . $config['themeName'] . '/header.php';
        require_once 'themes/' . $config['themeName'] . '/footer.php';

        $path = 'themes/' . $config['themeName'] . '/' . $this->controllerName . '/' . $this->controllerName . '_' . $this->methodName . '.html';

        $placeholderTemplate = file_get_contents($path);

        if ($this->placeholders) {
            foreach ($this->placeholders as $placeholder) {
                $mainPlaceholder = $placeholder['name'];
                $templateName = $placeholder['template'];
                $loop = $placeholder['loop'];
                $innerPlaceholders = $placeholder['innerPlaceholders'];

                $contentTemplate = '';

                if ($loop) {
                    foreach ($this->placeholderContent[$mainPlaceholder] as $entry) {
                        $pathLoop = 'Campaign/lib/templates/' . $templateName . '.html';
                        $loopTemplate = file_get_contents($pathLoop);
                        foreach ($innerPlaceholders as $innerPlaceholder) {
                            $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', $entry[strtolower($innerPlaceholder)], $loopTemplate);
                        }
                        $contentTemplate .=  $loopTemplate;
                    }
                }else {
                    if(!empty($innerPlaceholders)) {
                        $pathLoop = 'Campaign/lib/templates/' . $templateName . '.html';
                        $template = file_get_contents($pathLoop);
                        foreach ($innerPlaceholders as $innerPlaceholder) {
                            $template = str_replace('[[' . $innerPlaceholder . ']]', $this->placeholderContent[$mainPlaceholder], $template);
                        }
                        $contentTemplate .= $template;
                    }else {
                        $contentTemplate = $this->placeholderContent[$mainPlaceholder];
                    }
                }
                $placeholderTemplate = str_replace('[[' . $mainPlaceholder . ']]', $contentTemplate, $placeholderTemplate);
            }
        }
        if (file_exists($path)) {
            include_once('themes/' . $config['themeName'] . '/header.php');
            echo $placeholderTemplate;
            include_once('themes/' . $config['themeName'] . '/footer.php');
        } else {
            include_once ('themes/' . $config['themeName'] . '/error.php');
        }
    }
}