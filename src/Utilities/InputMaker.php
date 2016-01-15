<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * $this->elper to make an HTML input
 */
class InputMaker
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
    ];

    /**
     * Create the input HTML
     * @param  string   $name        Column/ Field name
     * @param  array    $field       Array of config info for item
     * @param  object   $object      Object or Table Object
     * @param  string   $class       CSS class
     * @param  boolean  $reformatted Clean the labels and placeholder values
     * @param  boolean  $populated   Set the value of the input to the object's value
     * @return string
     */
    public function create($name, $field, $object, $class, $reformatted = false, $populated)
    {
        $config = [];

        $config['populated'] = $populated;
        $config['name']      = $name;
        $config['class']     = $class;
        $config['field']     = $field;

        if (isset($field['class'])) {
            $config['class']     = $class.' '.$field['class'];
        }

        if (Config::get('form-maker')) {
            $config['inputTypes'] = Config::get('form-maker');
        } else {
            $config['inputTypes'] = include(__DIR__.'/../Published/config/form-maker.php');
        }
        $config['inputs']           = [];
        if (Session::isStarted()) {
            $config['inputs']           = Request::old();
        }
        $config['objectName']       = (isset($object->$name)) ? $object->$name : $name;
        $config['placeholder']      = $this->placeholder($field, $name);

        $config = $this->setConfigs($config, $reformatted, $name, $field);

        $inputString = '';

        if ($this->before($config) > '' || $this->after($config) > '') {
            $inputString .= '<div class="input-group">';
        }

        $inputString .= $this->before($config);
        $inputString .= $this->inputStringGenerator($config);
        $inputString .= $this->after($config);

        if ($this->before($config) > '' || $this->after($config) > '') {
            $inputString .= '</div>';
        }

        return $inputString;
    }

    /**
     * Before input
     * @param  array $config
     * @return string
     */
    public function before($config)
    {
        $before = (isset($config['field']['before'])) ? $config['field']['before'] : '';
        return $before;
    }

    /**
     * After input
     * @param  array $config
     * @return string
     */
    public function after($config)
    {
        $after = (isset($config['field']['after'])) ? $config['field']['after'] : '';
        return $after;
    }

    /**
     * Set the configs
     * @param array $config
     * @param bool $reformatted
     * @param string $name
     * @param array $field
     *
     * @return  array
     */
    public function setConfigs($config, $reformatted, $name, $field)
    {
        // If validation inputs are available lets prepopulate the fields!
        if (isset($config['inputs'][$name])) {
            $config['populated'] = true;
            $config['objectName'] = $config['inputs'][$name];
        }

        if ($reformatted) {
            $config['placeholder'] = $this->cleanString($this->placeholder($field, $name));
        }

        if ( ! isset($field['type'])) {
            if (is_array($field)) {
                $config['fieldType'] = 'string';
            } else {
                $config['fieldType'] = $field;
            }
        } else {
            $config['fieldType'] = $field['type'];
        }

        return $config;
    }

    /**
     * The input string generator
     * @param  array $config  Config
     * @return string
     */
    public function inputStringGenerator($config)
    {
        $textInputs     = ['text', 'textarea'];
        $selectInputs   = ['select'];
        $hiddenInputs   = ['hidden'];
        $checkboxInputs = ['checkbox', 'checkbox-inline'];
        $radioInputs    = ['radio', 'radio-inline'];

        $population = $this->getPopulation($config);
        $checkType = $this->checkType($config, $checkboxInputs);
        $selected = $this->isSelected($config, $checkType);
        $custom = $this->getCustom($config);

        switch ($config['fieldType']) {
            case in_array($config['fieldType'], $hiddenInputs):
                $inputString = $this->makeHidden($config, $population, $custom);
                break;

            case in_array($config['fieldType'], $textInputs):
                $inputString = $this->makeText($config, $population, $custom);
                break;

            case in_array($config['fieldType'], $selectInputs):
                $inputString = $this->makeSelected($config, $selected, $custom);
                break;

            case in_array($config['fieldType'], $checkboxInputs):
                $inputString = $this->makeCheckbox($config, $selected, $custom);
                break;

            case in_array($config['fieldType'], $radioInputs):
                $inputString = $this->makeRadio($config, $selected, $custom);
                break;

            default:
                // Pass along the config
                $config['type'] = $config['inputTypes'][$config['fieldType']];
                $inputString = $this->makeHTMLInputString($config);
                break;
        }

        return $inputString;
    }

    /**
     * Make a hidden input
     * @param  array $config
     * @param  string $population
     * @param  string $custom
     * @return string
     */
    public function makeHidden($config, $population, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" name="'.$config['name'].'" type="hidden" value="'.$population.'">';
    }

    /**
     * Make text input
     * @param  array $config
     * @param  string $population
     * @param  string $custom
     * @return string
     */
    public function makeText($config, $population, $custom)
    {
        return '<textarea '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" name="'.$config['name'].'" placeholder="'.$config['placeholder'].'">'.$population.'</textarea>';
    }

    /**
     * Make a select input
     * @param  array $config
     * @param  string $population
     * @param  string $custom
     * @return string
     */
    public function makeSelected($config, $population, $custom)
    {
        $options = '';
        foreach ($config['field']['options'] as $key => $value) {
            $selected = ((string) $config['objectName'] === (string) $value) ? 'selected' : '';
            $options .= '<option value="'.$value.'" '.$selected.'>'.$key.'</option>';
        }
        return '<select '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" name="'.$config['name'].'">'.$options.'</select>';
    }

    /**
     * Make a checkbox
     * @param  array $config
     * @param  string $selected
     * @param  string $custom
     * @return string
     */
    public function makeCheckbox($config, $selected, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" '.$selected.' type="checkbox" name="'.$config['name'].'">';
    }

    /**
     * Make a radio input
     * @param  array $config
     * @param  string $selected
     * @param  string $custom
     * @return string
     */
    public function makeRadio($config, $selected, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" '.$selected.' type="radio" name="'.$config['name'].'">';
    }

    /**
     * Get the content of the input
     * @param  array $config
     * @return string
     */
    public function getPopulation($config)
    {
        return ($config['populated']) ? $config['objectName'] : '';
    }

    /**
     * Has been selected
     * @param  array  $config
     * @param  string  $checkType Type of checkmark
     * @return boolean
     */
    public function isSelected($config, $checkType)
    {
        $selected = (isset($config['inputs'][$config['name']])
            || isset($config['field']['selected'])
            || $config['objectName'] === 'on'
            || $config['objectName'] == 1) ? $checkType : '';

        return $selected;
    }

    /**
     * Check type of checkbox/ radio
     * @param  array $config
     * @param  array $checkboxInputs
     * @return string
     */
    public function checkType($config, $checkboxInputs)
    {
        $checkType = (in_array($config['fieldType'], $checkboxInputs)) ? 'checked' : 'selected';
        return $checkType;
    }

    /**
     * Get Custom attributes
     * @param  array $config
     * @return string
     */
    public function getCustom($config)
    {
        $custom = (isset($config['field']['custom'])) ? $config['field']['custom'] : '';
        return $custom;
    }

    /**
     * Generate a standard HTML input string
     * @param  array $config        Config array
     * @return string
     */
    public function makeHTMLInputString($config)
    {
        $custom             = (isset($config['field']['custom'])) ? $config['field']['custom'] : '';
        $multiple           = (isset($config['field']['multiple'])) ? 'multiple' : '';
        $multipleArray      = (isset($config['field']['multiple'])) ? '[]' : '';
        $floatingNumber     = ($config['fieldType'] === 'float' || $config['fieldType'] === 'decimal') ? 'step="any"' : '';

        if (is_array($config['objectName']) && $config['type'] === 'file') {
            $population = '';
        } else {
            $population = ($config['populated'] && $config['name'] !== $config['objectName']) ? 'value="'.$config['objectName'].'"' : '';
        }

        $inputString        = '<input '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" type="'.$config['type'].'" name="'.$config['name'].$multipleArray.'" '.$floatingNumber.' '.$multiple.' '.$population.' placeholder="'.$config['placeholder'].'">';

        return $inputString;
    }

    /**
     * Create the placeholder
     * @param  array  $field  Field from Column Array
     * @param  string $column Column name
     * @return string
     */
    public function placeholder($field, $column)
    {
        if (! is_array($field) && ! in_array($field, $this->columnTypes)) {
            return ucfirst($field);
        }

        $alt_name = (isset($field['alt_name'])) ? $field['alt_name'] : ucfirst($column);
        $placeholder = (isset($field['placeholder'])) ? $field['placeholder'] : $alt_name;

        return $placeholder;
    }

    /**
     * Clean the string for the column name swap
     * @param  string $string Original column name
     * @return string
     */
    public function cleanString($string)
    {
        return ucwords(str_replace('_', ' ', $string));
    }
}