<?php

namespace Yab\Laracogs\Generators;

use Illuminate\Filesystem\Filesystem;

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
}
