<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * $this->elper to make an HTML input.
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
        'one-one',
        'one-many',
    ];

    /**
     * Create the input HTML.
     *
     * @param string $name        Column/ Field name
     * @param array  $field       Array of config info for item
     * @param object $object      Object or Table Object
     * @param string $class       CSS class
     * @param bool   $reformatted Clean the labels and placeholder values
     * @param bool   $populated   Set the value of the input to the object's value
     *
     * @return string
     */
    public function create($name, $field, $object = null, $class = 'form-control', $reformatted = false, $populated = true)
    {
        $config = [];

        $config['populated'] = $populated;
        $config['name'] = $name;
        $config['class'] = $class;
        $config['field'] = $field;

        if (isset($field['class'])) {
            $config['class'] = $class.' '.$field['class'];
        }

        $config['inputTypes'] = Config::get('form-maker', include(__DIR__.'/../Packages/Starter/config/form-maker.php'));

        $config['inputs'] = [];
        if (Session::isStarted()) {
            $config['inputs'] = Request::old();
        }

        $config['object'] = $object;
        $config['objectValue'] = (isset($object->$name) && !method_exists($object, $name)) ? $object->$name : $name;
        $config['placeholder'] = $this->placeholder($field, $name);

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
     * Create a label for an input.
     *
     * @param string $name
     * @param array  $config
     *
     * @return string
     */
    public function label($name, $attributes = [])
    {
        $attributeString = '';

        foreach ($attributes as $key => $value) {
            $attributeString .= $key.'="'.$value.'"';
        }

        return '<label '.$attributeString.'>'.$name.'</label>';
    }

    /*
    |--------------------------------------------------------------------------
    | Private Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Before input.
     *
     * @param array $config
     *
     * @return string
     */
    private function before($config)
    {
        $before = (isset($config['field']['before'])) ? $config['field']['before'] : '';

        return $before;
    }

    /**
     * After input.
     *
     * @param array $config
     *
     * @return string
     */
    private function after($config)
    {
        $after = (isset($config['field']['after'])) ? $config['field']['after'] : '';

        return $after;
    }

    /**
     * Set the configs.
     *
     * @param array  $config
     * @param bool   $reformatted
     * @param string $name
     * @param array  $field
     *
     * @return array
     */
    private function setConfigs($config, $reformatted, $name, $field)
    {
        // If validation inputs are available lets prepopulate the fields!
        if (!empty($config['inputs']) && isset($config['inputs'][$name])) {
            $config['populated'] = true;
            $config['objectValue'] = $config['inputs'][$name];
        }

        if ($reformatted) {
            $config['placeholder'] = $this->cleanString($this->placeholder($field, $name));
        }

        if (!isset($field['type'])) {
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
     * The input string generator.
     *
     * @param array $config Config
     *
     * @return string
     */
    private function inputStringGenerator($config)
    {
        $textInputs = ['text', 'textarea'];
        $selectInputs = ['select'];
        $hiddenInputs = ['hidden'];
        $checkboxInputs = ['checkbox', 'checkbox-inline'];
        $radioInputs = ['radio', 'radio-inline'];
        $relationshipInputs = ['relationship'];

        // Super Magic!
        if (strpos($config['objectValue'], '[') > 0 && $config['object']) {
            $final = $config['object'];
            $nameProperties = explode('[', $config['objectValue']);
            foreach ($nameProperties as $property) {
                $realProperty = str_replace(']', '', $property);
                $final = $final->$realProperty;
            }
            $config['objectValue'] = $final;
        }

        $population = $this->getPopulation($config);
        $checkType = $this->checkType($config, $checkboxInputs);
        $selected = $this->isSelected($config, $checkType);
        $custom = $this->getField($config, 'custom');

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

            case in_array($config['fieldType'], $relationshipInputs):
                $inputString = $this->makeRelationship($config, $this->getField($config, 'label', 'name'), $this->getField($config, 'value', 'id'), $custom);
                break;

            default:
                $config['type'] = $config['inputTypes']['string'];
                if (isset($config['inputTypes'][$config['fieldType']])) {
                    $config['type'] = $config['inputTypes'][$config['fieldType']];
                }
                $inputString = $this->makeHTMLInputString($config, $population);
                break;
        }

        return $inputString;
    }

    /*
    |--------------------------------------------------------------------------
    | Standard HTML Inputs
    |--------------------------------------------------------------------------
    */

    /**
     * Make a hidden input.
     *
     * @param array  $config
     * @param string $population
     * @param string $custom
     *
     * @return string
     */
    private function makeHidden($config, $population, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" name="'.$config['name'].'" type="hidden" value="'.$population.'">';
    }

    /**
     * Make text input.
     *
     * @param array  $config
     * @param string $population
     * @param string $custom
     *
     * @return string
     */
    private function makeText($config, $population, $custom)
    {
        return '<textarea '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" name="'.$config['name'].'" placeholder="'.$config['placeholder'].'">'.$population.'</textarea>';
    }

    /**
     * Make a select input.
     *
     * @param array  $config
     * @param string $population
     * @param string $custom
     *
     * @return string
     */
    private function makeSelected($config, $selected, $custom)
    {
        $options = '';
        foreach ($config['field']['options'] as $key => $value) {
            if ($selected == '') {
                $selectedValue = ((string) $config['objectValue'] === (string) $value) ? 'selected' : '';
            } else {
                $selectedValue = ((string) $selected === (string) $value) ? 'selected' : '';
            }
            $options .= '<option value="'.$value.'" '.$selectedValue.'>'.$key.'</option>';
        }

        return '<select '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" name="'.$config['name'].'">'.$options.'</select>';
    }

    /**
     * Make a checkbox.
     *
     * @param array  $config
     * @param string $selected
     * @param string $custom
     *
     * @return string
     */
    private function makeCheckbox($config, $selected, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" '.$selected.' type="checkbox" name="'.$config['name'].'">';
    }

    /**
     * Make a radio input.
     *
     * @param array  $config
     * @param string $selected
     * @param string $custom
     *
     * @return string
     */
    private function makeRadio($config, $selected, $custom)
    {
        return '<input '.$custom.' id="'.ucfirst($config['name']).'" '.$selected.' type="radio" name="'.$config['name'].'">';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship based
    |--------------------------------------------------------------------------
    */

    /**
     * Make a relationship input.
     *
     * @param array  $config
     * @param string $selected
     * @param string $custom
     *
     * @return string
     */
    private function makeRelationship($config, $label = 'name', $value = 'id', $custom = '')
    {
        $object = $config['object'];
        $relationship = $config['name'];

        $class = app()->make($config['field']['model']);
        $items = $class->all();

        foreach ($items as $item) {
            $config['field']['options'][$item->$label] = $item->$value;
        }

        $selected = '';
        if (is_object($object) && $object->$relationship()->first()) {
            $selected = $object->$relationship()->first()->$value;
        }

        return $this->makeSelected($config, $selected, $custom);
    }

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */

    /**
     * Get the content of the input.
     *
     * @param array $config
     *
     * @return string
     */
    private function getPopulation($config)
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
    private function isSelected($config, $checkType)
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
    private function checkType($config, $checkboxInputs)
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
    private function getField($config, $field, $default = '')
    {
        $data = (isset($config['field'][$field])) ? $config['field'][$field] : $default;

        return $data;
    }

    /**
     * Generate a standard HTML input string.
     *
     * @param array $config Config array
     *
     * @return string
     */
    private function makeHTMLInputString($config)
    {
        $custom = (isset($config['field']['custom'])) ? $config['field']['custom'] : '';
        $multiple = (isset($config['field']['multiple'])) ? 'multiple' : '';
        $multipleArray = (isset($config['field']['multiple'])) ? '[]' : '';
        $floatingNumber = ($config['fieldType'] === 'float' || $config['fieldType'] === 'decimal') ? 'step="any"' : '';

        if (is_array($config['objectValue']) && $config['type'] === 'file') {
            $population = '';
        } else {
            $population = ($config['populated'] && $config['name'] !== $config['objectValue']) ? 'value="'.$config['objectValue'].'"' : '';
        }

        $inputString = '<input '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" type="'.$config['type'].'" name="'.$config['name'].$multipleArray.'" '.$floatingNumber.' '.$multiple.' '.$population.' placeholder="'.$config['placeholder'].'">';

        return $inputString;
    }

    /**
     * Create the placeholder.
     *
     * @param array  $field  Field from Column Array
     * @param string $column Column name
     *
     * @return string
     */
    private function placeholder($field, $column)
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
