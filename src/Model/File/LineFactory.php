<?php

namespace Droid\Lib\Plugin\Model\File;

class LineFactory
{
    private $lineClassName;
    private $fieldSeparator;
    private $mappingFields;

    public function __construct(
        $lineClassName,
        $fieldSeparator = ' '
    ) {
        $this->lineClassName = $lineClassName;
        $this->fieldSeparator = $fieldSeparator;
    }

    public function getFieldSeparator()
    {
        return $this->fieldSeparator;
    }

    public function setFieldSeparator($fieldSeparator)
    {
        $this->fieldSeparator = $fieldSeparator;
    }

    public function getMappingFields()
    {
        return $this->mappingFields;
    }

    public function setMappingFields($mappingFields)
    {
        $this->mappingFields = $mappingFields;
    }

    public function makeLine()
    {
        $line = new $this->lineClassName;
        $line->setFieldSeparator($this->fieldSeparator);
        if ($this->mappingFields) {
            $line->setMappingFields($this->mappingFields);
        }
        return $line;
    }
}
