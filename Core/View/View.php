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

    public function __construct(string $controllerName,string $methodName, array $contentPlaceholders, array $headerPlaceholders, array $footerPlaceholders)
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
        require_once 'themes/' . $this->config['themeName'] . '/footer.html';

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
        print $contentTemplate;
    }

    protected function loadHeader()
    {
        $path = 'themes/' . $this->config['themeName'] . '/header.html';

        $headerTemplate = file_get_contents($path);
        if (!empty($this->headerPlaceholders)) {
            $headerTemplate = $this->fillPlaceholdersWithContent($headerTemplate, $this->headerPlaceholders, 'Core');
        }

        print $headerTemplate;
    }

    protected function loadFooter()
    {
        $path = 'themes/' . $this->config['themeName'] . '/footer.html';

        $footerTemplate = file_get_contents($path);
        if (!empty($this->footerPlaceholders)) {
            $footerTemplate = $this->fillPlaceholdersWithContent($footerTemplate, $this->footerPlaceholders, 'Core');
        }
        print $footerTemplate;
    }

    protected function fillPlaceholdersWithContent(string $fileTemplate, array $placeholders, string $module = '')
    {
        if (empty($module)) {
            $module = $this->controllerName;
        }
        foreach ($placeholders as $placeholder) {
            $mainPlaceholder = $placeholder['name'];
            $templateName = $placeholder['template'];
            $type = $placeholder['type'];
            $innerPlaceholders = $placeholder['innerPlaceholders'];
            $placeholderContent = $placeholder['placeholderContent'];

            $contentTemplate = '';

            if ($type == 'loop' || $type == 'object-loop') {
                foreach ($placeholderContent as $entry) {
                    $pathLoop = ucfirst($module) . '/lib/templates/' . $templateName . '.html';
                    $loopTemplate = file_get_contents($pathLoop);
                    foreach ($innerPlaceholders as $innerPlaceholder) {
                        if ($type == 'object-loop') {
                            $methodeNameForGet = 'get' . $innerPlaceholder;
                            $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($entry->$methodeNameForGet()), $loopTemplate);
                        } else {
                            $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($entry[strtolower($innerPlaceholder)]), $loopTemplate);
                        }
                    }
                    $contentTemplate .= $loopTemplate;
                }
                $fileTemplate = str_replace('[[' . $mainPlaceholder . ']]', $contentTemplate, $fileTemplate);
            } else if ($type == 'area') {
                $startPlaceholder = '<'. $mainPlaceholder .'>';
                $endPlaceholder = '</'. $mainPlaceholder .'>';
                if ($placeholderContent['isTrue']) {
                    $newContent = $placeholderContent['replace'];
                    $contentTemplate = preg_replace('#(' . preg_quote($startPlaceholder) . ')(.*?)(' . preg_quote($endPlaceholder) . ')#si', '$1' . $newContent . '$3', $fileTemplate);
                    $fileTemplate = $contentTemplate;
                }

            } else {
                if (!empty($innerPlaceholders)) {
                    $pathPlaceholder = ucfirst($module) . '/lib/templates/' . $templateName . '.html';
                    $templatePlaceholder = file_get_contents($pathPlaceholder);
                    foreach ($innerPlaceholders as $innerPlaceholder) {
                        if (array_key_exists(strtolower($innerPlaceholder),$placeholderContent)) {
                            $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($placeholderContent[strtolower($innerPlaceholder)]), $templatePlaceholder);
                        } else {
                            $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', '', $templatePlaceholder);
                        }
                    }
                    $contentTemplate = $templatePlaceholder;
                } else {
                    if (empty($placeholderContent)) {
                        $pathPlaceholder = ucfirst($module) . '/lib/templates/' . $templateName . '.html';
                        $templatePlaceholder = file_get_contents($pathPlaceholder);
                        $contentTemplate = $templatePlaceholder;
                    } else {
                        $contentTemplate = $placeholderContent;
                    }
                }
                if (!empty($contentTemplate)) {
                    $fileTemplate = str_replace('[[' . $mainPlaceholder . ']]', $contentTemplate, $fileTemplate);
                } else {
                    $fileTemplate = str_replace('[[' . $mainPlaceholder . ']]', '', $fileTemplate);
                }
            }
        }
        return $fileTemplate;
    }
}