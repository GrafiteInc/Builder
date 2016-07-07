<?php

namespace Yab\Laracogs\Generators;

/**
 * Generate the CRUD.
 */
class HtmlGenerator
{
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
    public function makeHidden($config, $population, $custom)
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
    public function makeText($config, $population, $custom)
    {
        return '<textarea '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" name="'.$config['name'].'" placeholder="'.$config['placeholder'].'">'.$population.'</textarea>';
    }

    /**
     * Make a select input.
     *
     * @param array  $config
     * @param string $selected
     * @param string $custom
     *
     * @return string
     */
    public function makeSelected($config, $selected, $custom)
    {
        $options = '';
        foreach ($config['config']['options'] as $key => $value) {
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
    public function makeCheckbox($config, $selected, $custom)
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
    public function makeRadio($config, $selected, $custom)
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
     * @param string $label
     * @param string $value
     * @param string $custom
     *
     * @return string
     */
    public function makeRelationship($config, $label = 'name', $value = 'id', $custom = '')
    {
        $object = $config['object'];
        $relationship = $config['name'];

        $class = app()->make($config['config']['model']);
        $items = $class->all();

        foreach ($items as $item) {
            $config['config']['options'][$item->$label] = $item->$value;
        }

        $selected = '';
        if (is_object($object) && $object->$relationship()->first()) {
            $selected = $object->$relationship()->first()->$value;
        }

        return $this->makeSelected($config, $selected, $custom);
    }

    /**
     * Generate a standard HTML input string.
     *
     * @param array $config Config array
     *
     * @return string
     */
    public function makeHTMLInputString($config)
    {
        $custom = $this->getCustom($config);
        $multiple = $this->isMultiple($config, 'multiple');
        $multipleArray = $this->isMultiple($config, '[]');
        $floatingNumber = $this->getFloatingNumber($config);
        $population = $this->getPopulation($config);

        if (is_array($config['objectValue']) && $config['type'] === 'file') {
            $population = '';
        }

        $inputString = '<input '.$custom.' id="'.ucfirst($config['name']).'" class="'.$config['class'].'" type="'.$config['type'].'" name="'.$config['name'].$multipleArray.'" '.$floatingNumber.' '.$multiple.' '.$population.' placeholder="'.$config['placeholder'].'">';

        return $inputString;
    }

    /**
     * Is the config a multiple?
     *
     * @param  array $config
     * @param  string $response
     * @return boolean
     */
    public function isMultiple($config, $response)
    {
        if (isset($config['config']['multiple'])) {
            return $response;
        }

        return '';
    }

    /**
     * Get the population.
     *
     * @param  array $config
     * @return string
     */
    public function getPopulation($config)
    {
        if ($config['populated'] && $config['name'] !== $config['objectValue']) {
            return 'value="'.$config['objectValue'].'"';
        }

        return '';
    }

    /**
     * Get the custom details.
     *
     * @param  array $config
     * @return string
     */
    public function getCustom($config)
    {
        if (isset($config['config']['custom'])) {
            return $config['config']['custom'];
        }

        return '';
    }

    /**
     * Get the floating number.
     *
     * @param  array $config
     * @return string
     */
    public function getFloatingNumber($config)
    {
        if ($config['inputType'] === 'float' || $config['inputType'] === 'decimal') {
            return 'step="any"';
        }

        return '';
    }
}
