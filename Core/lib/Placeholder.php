<?php

/**
 * Created by PhpStorm.
 * User: sam.hawkes
 * Date: 19.01.2018
 * Time: 10:50
 */
class Placeholder
{
    protected $name;
    protected $templatePath;
    protected $type;
    protected $innerPlaceholders;
    protected $placeholderContent;
    protected $contentTemplate;
    protected $modul;
    protected $htmlNotSpecial;

    public function __construct(string $name, string $templatePath, string $type = '', array $innerPlaceholders = array(), array $placeholderContent = array(), bool $htmlNotSpecial = false)
    {
        $this->name = $name;
        $this->templatePath = $templatePath;
        $this->type = $type;
        $this->innerPlaceholders = $innerPlaceholders;
        $this->placeholderContent = $placeholderContent;
        $this->htmlNotSpecial = $htmlNotSpecial;
    }

    public function getPlaceholder()
    {
        $placeholder = array (
            'name' => $this->name,
            'template' => $this->templatePath,
            'type' => $this->type,
            'innerPlaceholders' => $this->innerPlaceholders,
            'placeholderContent' => $this->placeholderContent,
        );
        return $placeholder;
    }

    public function fillContent(string $fileTemplate, string $modul = '')
    {
        $this->modul = $modul;
        $this->fileTemplate = $fileTemplate;
        $this->contentTemplate = '';

        if ($this->type == 'loop' || $this->type == 'object-loop') {
            $this->fillLoopContent();
        } else if ($this->type == 'area') {
            $this->fillAreaContent();
        } else {
            if (!empty($this->innerPlaceholders)) {
                $this->fillWithInnerPlaceholders();
            } else {
                $this->fillWithoutInnerPlaceholders();
            }
            if (!empty($this->contentTemplate)) {
                $this->fileTemplate = str_replace('[[' . $this->name . ']]', $this->contentTemplate, $this->fileTemplate);
            } else {
                $this->fileTemplate = str_replace('[[' . $this->name . ']]', '', $this->fileTemplate);
            }
        }
        return $this->fileTemplate;
    }

    protected function fillLoopContent()
    {
        foreach ($this->placeholderContent as $entry) {
            $pathLoop = ucfirst($this->modul) . '/lib/templates/' . $this->templatePath . '.html';
            $loopTemplate = file_get_contents($pathLoop);

            foreach ($this->innerPlaceholders as $innerPlaceholder) {
                if ($this->type == 'object-loop') {
                    $methodeNameForGet = 'get' . $innerPlaceholder;
                    if ($this->htmlNotSpecial) {
                        $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', $entry->$methodeNameForGet(), $loopTemplate);
                    } else {
                        $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($entry->$methodeNameForGet()), $loopTemplate);
                    }
                } else {
                    if ($this->htmlNotSpecial) {
                        $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', $entry[strtolower($innerPlaceholder)], $loopTemplate);
                    } else {
                        $loopTemplate = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($entry[strtolower($innerPlaceholder)]), $loopTemplate);
                    }
                }
            }
            $this->contentTemplate .= $loopTemplate;
        }
        $this->fileTemplate = str_replace('[[' . $this->name . ']]', $this->contentTemplate, $this->fileTemplate);
    }

    protected function fillAreaContent()
    {
        $startPlaceholder = '<'. $this->name .'>';
        $endPlaceholder = '</'. $this->name .'>';
        if ($this->placeholderContent['isTrue']) {
            $newContent = $this->placeholderContent['replace'];
            $this->contentTemplate = preg_replace('#(' . preg_quote($startPlaceholder) . ')(.*?)(' . preg_quote($endPlaceholder) . ')#si', '$1' . $newContent . '$3', $this->fileTemplate);
            $this->fileTemplate = $this->contentTemplate;
        }
    }

    protected function fillWithInnerPlaceholders()
    {
        $pathPlaceholder = ucfirst($this->modul) . '/lib/templates/' . $this->templatePath . '.html';
        $templatePlaceholder = file_get_contents($pathPlaceholder);
        foreach ($this->innerPlaceholders as $innerPlaceholder) {
            if (array_key_exists(strtolower($innerPlaceholder),$this->placeholderContent)) {
                if ($this->htmlNotSpecial) {
                    $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', $this->placeholderContent[strtolower($innerPlaceholder)], $templatePlaceholder);
                } else {
                    $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', htmlspecialchars($this->placeholderContent[strtolower($innerPlaceholder)]), $templatePlaceholder);
                }
            } else {
                $templatePlaceholder = str_replace('[[' . $innerPlaceholder . ']]', '', $templatePlaceholder);
            }
        }
        $this->contentTemplate = $templatePlaceholder;
    }

    protected function fillWithoutInnerPlaceholders()
    {
        if (!empty($this->templatePath)) {
            $pathPlaceholder = ucfirst($this->modul) . '/lib/templates/' . $this->templatePath . '.html';
            $templatePlaceholder = file_get_contents($pathPlaceholder);
            $this->contentTemplate = $templatePlaceholder;
        } else {
            $this->contentTemplate = $this->placeholderContent[strtolower($this->name)];
        }
    }
}