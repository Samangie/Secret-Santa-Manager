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
            $fileTemplate = $placeholder->fillContent($fileTemplate, $module);
        }
        return $fileTemplate;
    }
}