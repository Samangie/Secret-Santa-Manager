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
    protected $contentPlaceholders;
    protected $headerPlaceholders;
    protected $footerPlaceholders;
    protected $config;

    public function __construct($controllerName,$methodName, $contentPlaceholders, $headerPlaceholders, $footerPlaceholders)
    {
        $this->controllerName = $controllerName;
        $this->methodName = $methodName;
        $this->contentPlaceholders = $contentPlaceholders;
        $this->headerPlaceholders = $headerPlaceholders;
        $this->footerPlaceholders = $footerPlaceholders;

        $this->config = require 'config.php';
        $this->display();
    }

    protected function display()
    {
        require_once 'themes/' . $this->config['themeName'] . '/footer.php';

        $this->loadHeader();
        $this->loadContent();
        $this->loadFooter();
    }

    protected function loadContent()
    {
        $path = 'themes/' . $this->config['themeName'] . '/' . $this->controllerName . '/' . $this->controllerName . '_' . $this->methodName . '.html';

        $contentTemplate = file_get_contents($path);
        if (!empty($this->contentPlaceholders)) {
            $contentTemplate = $this->fillPlaceholdersWithContent($contentTemplate, $this->contentPlaceholders);
        }
        echo $contentTemplate;
    }

    protected function loadHeader()
    {
        $path = 'themes/' . $this->config['themeName'] . '/header.php';

        $headerTemplate = file_get_contents($path);
        if (!empty($this->headerPlaceholders)) {
            $headerTemplate = $this->fillPlaceholdersWithContent($headerTemplate, $this->headerPlaceholders, 'Core');
        }

        echo $headerTemplate;
    }

    protected function loadFooter()
    {
        $path = 'themes/' . $this->config['themeName'] . '/footer.php';

        $footerTemplate = file_get_contents($path);
        if (!empty($this->footerPlaceholders)) {
            $footerTemplate = $this->fillPlaceholdersWithContent($footerTemplate, $this->footerPlaceholders, 'Core');
        }
        echo $footerTemplate;
    }

    protected function fillPlaceholdersWithContent($fileTemplate, $placeholders, $module = null)
    {
        if (empty($module)) {
            $module = $this->controllerName;
        }

        foreach ($placeholders as $placeholder) {
            $mainPlaceholder = $placeholder['name'];
            $templateName = $placeholder['template'];
            $loop = $placeholder['loop'];
            $innerPlaceholders = $placeholder['innerPlaceholders'];
            $placeholderContent = $placeholder['placeholderContent'];

            $contentTemplate = '';

            if ($loop) {
                foreach ($placeholderContent as $entry) {
                    $pathLoop = ucfirst($module) . '/lib/templates/' . $templateName . '.html';
                    $loopTemplate = file_get_contents($pathLoop);
                    foreach ($innerPlaceholders as $innerPlaceholder) {
                        $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', $entry[strtolower($innerPlaceholder)], $loopTemplate);
                    }
                    $contentTemplate .= $loopTemplate;
                }
            } else {
                if(!empty($innerPlaceholders)) {
                    $pathPlaceholder = ucfirst($module) . '/lib/templates/' . $templateName . '.html';
                    $templatePlaceholder = file_get_contents($pathPlaceholder);
                    foreach ($innerPlaceholders as $innerPlaceholder) {
                        $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', $placeholderContent[strtolower($innerPlaceholder)], $templatePlaceholder);
                    }
                    $contentTemplate .= $templatePlaceholder;
                }else {
                    $contentTemplate = $placeholderContent;
                }
            }
            $fileTemplate = str_replace('[[' . $mainPlaceholder . ']]', $contentTemplate, $fileTemplate);
        }
        return $fileTemplate;
    }
}

