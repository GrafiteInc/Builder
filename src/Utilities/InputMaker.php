<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Yab\Laracogs\Generators\HtmlGenerator;
use Yab\Laracogs\Utilities\InputUtilities;

/**
 * $this->elper to make an HTML input.
 */
class InputMaker
{
    protected $htmlGenerator;

    protected $inputUtilities;

    public function __construct()
    {
        $this->htmlGenerator = new HtmlGenerator();
        $this->inputUtilities = new InputUtilities();
    }

    /**
     * Create the input HTML.
     *
     * @param string       $name        Column/ Field name
     * @param array        $field       Array of config info for item
     * @param object|array $object      Object or Table Object
     * @param string       $class       CSS class
     * @param bool         $reformatted Clean the labels and placeholder values
     * @param bool         $populated   Set the value of the input to the object's value
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
        $config['placeholder'] = $this->inputUtilities->placeholder($field, $name);

        $config = $this->setConfigs($config, $reformatted, $name, $field);

        return $this->inputStringPreparer($config);
    }

    /**
     * Input string preparer
     *
     * @param  array $config
     * @return string
     */
    public function inputStringPreparer($config)
    {
        $inputString = '';
        $beforeAfterCondition = ($this->before($config) > '' || $this->after($config) > '');

        if ($beforeAfterCondition) {
            $inputString .= '<div class="input-group">';
        }

        $inputString .= $this->before($config);
        $inputString .= $this->inputStringGenerator($config);
        $inputString .= $this->after($config);

        if ($beforeAfterCondition) {
            $inputString .= '</div>';
        }

        return $inputString;
    }

    /**
     * Create a label for an input.
     *
     * @param string $name
     * @param array  $attributes
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
            $config['placeholder'] = $this->inputUtilities->cleanString($this->inputUtilities->placeholder($field, $name));
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

        $population = $this->inputUtilities->getPopulation($config);
        $checkType = $this->inputUtilities->checkType($config, $checkboxInputs);
        $selected = $this->inputUtilities->isSelected($config, $checkType);
        $custom = $this->inputUtilities->getField($config, 'custom');

        switch ($config['fieldType']) {
            case in_array($config['fieldType'], $hiddenInputs):
                $inputString = $this->htmlGenerator->makeHidden($config, $population, $custom);
                break;

            case in_array($config['fieldType'], $textInputs):
                $inputString = $this->htmlGenerator->makeText($config, $population, $custom);
                break;

            case in_array($config['fieldType'], $selectInputs):
                $inputString = $this->htmlGenerator->makeSelected($config, $selected, $custom);
                break;

            case in_array($config['fieldType'], $checkboxInputs):
                $inputString = $this->htmlGenerator->makeCheckbox($config, $selected, $custom);
                break;

            case in_array($config['fieldType'], $radioInputs):
                $inputString = $this->htmlGenerator->makeRadio($config, $selected, $custom);
                break;

            case in_array($config['fieldType'], $relationshipInputs):
                $inputString = $this->htmlGenerator->makeRelationship(
                    $config,
                    $this->inputUtilities->getField($config, 'label', 'name'),
                    $this->inputUtilities->getField($config, 'value', 'id'),
                    $custom
                );
                break;

            default:
                $config['type'] = $config['inputTypes']['string'];
                if (isset($config['inputTypes'][$config['fieldType']])) {
                    $config['type'] = $config['inputTypes'][$config['fieldType']];
                }
                $inputString = $this->htmlGenerator->makeHTMLInputString($config);
                break;
        }

        return $inputString;
    }
}
