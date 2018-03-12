<?php

namespace Corcel\Acf\Repositories;

use Corcel\Model\Post;
use Corcel\Acf\Field\Repeater;
use Corcel\Acf\Field\FlexibleContent;
use Corcel\Acf\Models\AcfField;

abstract class Repository
{
    public function __construct()
    {
    }

    abstract public function fetchValue($field);

    abstract public function getConnectionName();

    abstract public function repeaterFetchFields(Repeater $repeater);
    abstract public function flexibleContentFetchFields(FlexibleContent $fc);

    abstract public function getFieldKey(string $fieldName);

    public function getFieldType($fieldName)
    {
        $key = $this->getFieldKey($fieldName);

        if ($key === null) { // Field does not exist
            return null;
        }

        $field = AcfField::where('post_name', $key)->first();

        if (!$field) {
            return null;
        }

        return $field->type;
    }

    /**
     * @param string $metaKey
     * @param string $fieldName
     *
     * @return int
     */
    public function retrieveIdFromFieldName($metaKey, $fieldName)
    {
        return (int) str_replace("{$fieldName}_", '', $metaKey);
    }

    /**
     * @param string $metaKey
     * @param string $fieldName
     * @param int    $id
     *
     * @return string
     */
    public function retrieveFieldName($metaKey, $fieldName, $id)
    {
        $pattern = "{$fieldName}_{$id}_";

        return str_replace($pattern, '', $metaKey);
    }
}
