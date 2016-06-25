<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Yab\Laracogs\Generators\HtmlGenerator;

/**
 * $this->elper to make an HTML input.
 */
class InputUtilities
{
    protected $columnTypes = [
        'integer',
        'string',
        'datetime',
        'date',
        'float',
        'binary',
        'blob',
        'boolean',
        'datetimetz',
        'time',
        'array',
        'json_array',
        'object',
        'decimal',
        'bigint',
        'smallint',
        'one-one',
        'one-many',
    ];

    /**
     * Get the content of the input.
     *
     * @param array $config
     *
     * @return string
     */
    public function getPopulation($config)
    {
        return ($config['populated']) ? $config['objectValue'] : '';
    }

    /**
     * Has been selected.
     *
     * @param array  $config
     * @param string $checkType Type of checkmark
     *
     * @return bool
     */
    public function isSelected($config, $checkType)
    {
        $selected = (isset($config['inputs'][$config['name']])
            || isset($config['field']['selected'])
            || $config['objectValue'] === 'on'
            || $config['objectValue'] == 1) ? $checkType : '';

        return $selected;
    }

    /**
     * Check type of checkbox/ radio.
     *
     * @param array $config
     * @param array $checkboxInputs
     *
     * @return string
     */
    public function checkType($config, $checkboxInputs)
    {
        $checkType = (in_array($config['fieldType'], $checkboxInputs)) ? 'checked' : 'selected';

        return $checkType;
    }

    /**
     * Get attributes.
     *
     * @param array $config
     *
     * @return string
     */
    public function getField($config, $field, $default = '')
    {
        $data = (isset($config['field'][$field])) ? $config['field'][$field] : $default;

        return $data;
    }

    /**
     * Create the placeholder.
     *
     * @param array  $field  Field from Column Array
     * @param string $column Column name
     *
     * @return string
     */
    public function placeholder($field, $column)
    {
        if (!is_array($field) && !in_array($field, $this->columnTypes)) {
            return ucfirst($field);
        }

        if (strpos($column, '[') > 0) {
            preg_match_all("/\[([^\]]*)\]/", $column, $matches);
            $column = $matches[1][0];
        }

        $alt_name = (isset($field['alt_name'])) ? $field['alt_name'] : ucfirst($column);
        $placeholder = (isset($field['placeholder'])) ? $field['placeholder'] : $alt_name;

        return $placeholder;
    }

    /**
     * Clean the string for the column name swap.
     *
     * @param string $string Original column name
     *
     * @return string
     */
    public function cleanString($string)
    {
        return ucwords(str_replace('_', ' ', $string));
    }
}
