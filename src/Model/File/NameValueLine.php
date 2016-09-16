<?php

namespace Droid\Lib\Plugin\Model\File;

use DomainException;

class NameValueLine extends AbstractLine
{
    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    protected $mappingFields = array(self::FIELD_NAME);

    public function getMappingValues()
    {
        $f = function ($fieldName) {
            return $this->getFieldValue($fieldName);
        };

        return array_map(
            $f->bindTo($this),
            $this->mappingFields
        );
    }

    public function setMappingFields(array $fields)
    {
        $this->mappingFields = $fields;
        return $this;
    }

    /**
     * Parsed a simple "name", "separator", "value" line into a name value pair.
     *
     * @param string $data
     *
     * @return array
     */
    protected function parse($data)
    {
        $parsed = array();

        // Split into parts
        $parts = explode(
            $this->normaliseWhitespace($this->fieldSeparator),
            $this->normaliseWhitespace(trim($data, " \t"))
        );
        if (sizeof($parts) < 2) {
            throw new DomainException(
                sprintf(
                    'Expected a well-formed line of two fields "name%svalue", got: "%s".',
                    $this->fieldSeparator,
                    $this->originalLine
                )
            );
        }

        $parsed[self::FIELD_NAME] = array_shift($parts);

        $value = implode($this->fieldSeparator, $parts);
        if (substr($value, 0, 1) === '"' && substr($value, -1) === '"') {
            $value = substr($value, 1, -1);
        }
        $parsed[self::FIELD_VALUE] = $value;

        return $parsed;
    }

    protected function normaliseWhitespace($data)
    {
        $data = str_replace("\t", ' ', $data);
        while (true) {
            $prev = $data;
            $data = str_replace('  ', ' ', $data);
            if ($data === $prev) {
                break;
            }
        }
        return $data;
    }
}
