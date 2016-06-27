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

    protected $inputGroups = [
        'text' => [
            'text',
            'textarea'
        ],
        'select' => [
            'select'
        ],
        'hidden' => [
            'hidden'
        ],
        'checkbox' => [
            'checkbox',
            'checkbox-inline'
        ],
        'radio' => [
            'radio',
            'radio-inline'
        ],
        'relationship' => [
            'relationship'
        ],
    ];

    public function __construct()
    {
        $this->htmlGenerator = new HtmlGenerator();
        $this->inputUtilities = new InputUtilities();
    }

    /**
     * Create the input HTML.
     *
     * @param string       $name        Column/ Input name
     * @param array        $config      Array of config info for the input
     * @param object|array $object      Object or Table Object
     * @param string       $class       CSS class
     * @param bool         $reformatted Clean the labels and placeholder values
     * @param bool         $populated   Set the value of the input to the object's value
     *
     * @return string
     */
    public function create($name, $config, $object = null, $class = 'form-control', $reformatted = false, $populated = true)
    {
        $inputConfig = [
            'populated' => $populated,
            'name' => $name,
            'class' => $this->prepareTheClass($class, $config),
            'config' => $config,
            'inputTypes' => Config::get('form-maker', include(__DIR__.'/../Packages/Starter/config/form-maker.php')),
            'inputs' => $this->getInput(),
            'object' => $object,
            'objectValue' => (isset($object->$name) && !method_exists($object, $name)) ? $object->$name : $name,
            'placeholder' => $this->inputUtilities->placeholder($config, $name),
        ];

        $inputConfig = $this->refineConfigs($inputConfig, $reformatted, $name, $config);

        return $this->inputStringPreparer($inputConfig);
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

    /**
     * Before input.
     *
     * @param array $config
     *
     * @return string
     */
    private function before($config)
    {
        $before = (isset($config['config']['before'])) ? $config['config']['before'] : '';

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
        $after = (isset($config['config']['after'])) ? $config['config']['after'] : '';

        return $after;
    }

    /**
     * Prepare the input class.
     *
     * @param  string $class
     * @return string
     */
    public function prepareTheClass($class, $config)
    {
        $finalizedClass = $class;

        if (isset($config['class'])) {
            $finalizedClass .= ' '.$config['class'];
        }

        return $finalizedClass;
    }

    /**
     * Get inputs.
     *
     * @return array
     */
    public function getInput()
    {
        $input = [];

        if (Session::isStarted()) {
            $input = Request::old();
        }

        return $input;
    }

    /**
     * Set the configs.
     *
     * @param array  $config
     * @param bool   $reformatted
     * @param string $name
     * @param array  $config
     *
     * @return array
     */
    private function refineConfigs($inputConfig, $reformatted, $name, $config)
    {
        // If validation inputs are available lets prepopulate the fields!
        if (!empty($inputConfig['inputs']) && isset($inputConfig['inputs'][$name])) {
            $inputConfig['populated'] = true;
            $inputConfig['objectValue'] = $inputConfig['inputs'][$name];
        }

        if ($reformatted) {
            $inputConfig['placeholder'] = $this->inputUtilities->cleanString($this->inputUtilities->placeholder($config, $name));
        }

        if (!isset($config['type'])) {
            if (is_array($config)) {
                $inputConfig['inputType'] = 'string';
            } else {
                $inputConfig['inputType'] = $config;
            }
        } else {
            $inputConfig['inputType'] = $config['type'];
        }

        return $inputConfig;
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
        $checkType = $this->inputUtilities->checkType($config, $this->inputGroups['checkbox']);
        $selected = $this->inputUtilities->isSelected($config, $checkType);
        $custom = $this->inputUtilities->getField($config, 'custom');
        $method = $this->getGeneratorMethod($config['inputType']);

        $standardMethods = [
            'makeHidden',
            'makeText',
            'makeSelected',
            'makeCheckbox',
            'makeRadio',
        ];

        if (in_array($method, $standardMethods)) {
            $inputString = $this->htmlGenerator->$method($config, $population, $custom);
        } elseif ($method === 'makeRelationship') {
            $inputString = $this->htmlGenerator->makeRelationship(
                $config,
                $this->inputUtilities->getField($config, 'label', 'name'),
                $this->inputUtilities->getField($config, 'value', 'id'),
                $custom
            );
        } else {
            $config['type'] = $config['inputTypes']['string'];
            if (isset($config['inputTypes'][$config['inputType']])) {
                $config['type'] = $config['inputTypes'][$config['inputType']];
            }
            $inputString = $this->htmlGenerator->makeHTMLInputString($config);
        }

        return $inputString;
    }

    /**
     * Get the generator method.
     *
     * @param  string $type
     * @return string
     */
    public function getGeneratorMethod($type)
    {
        $method = '';

        switch ($type) {
            case in_array($type, $this->inputGroups['hidden']):
                $method = 'makeHidden';
                break;

            case in_array($type, $this->inputGroups['text']):
                $method = 'makeText';
                break;

            case in_array($type, $this->inputGroups['select']):
                $method = 'makeSelected';
                break;

            case in_array($type, $this->inputGroups['checkbox']):
                $method = 'makeCheckbox';
                break;

            case in_array($type, $this->inputGroups['radio']):
                $method = 'makeRadio';
                break;

            case in_array($type, $this->inputGroups['relationship']):
                $method = 'makeRelationship';
                break;

            default:
                $method = 'makeHTMLInputString';
                break;
        }

        return $method;
    }
}
