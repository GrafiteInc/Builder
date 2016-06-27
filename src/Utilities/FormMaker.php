<?php

namespace Yab\Laracogs\Utilities;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * FormMaker helper to make table and object form mapping easy.
 */
class FormMaker
{
    protected $inputMaker;

    protected $inputUtilities;

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
        'relationship',
    ];

    public function __construct()
    {
        $this->inputMaker = new InputMaker();
        $this->inputUtilities = new InputUtilities();
    }

    /**
     * Generate a form from a table.
     *
     * @param string $table           Table name
     * @param array  $columns         Array of columns and details regarding them see config/forms.php for examples
     * @param string $class           Class names to be given to the inputs
     * @param string $view            View to use - for custom form layouts
     * @param bool   $reformatted     Corrects the table column names to clean words if no columns array provided
     * @param bool   $populated       Populates the inputs with the column names as values
     * @param bool   $idAndTimestamps Allows id and Timestamp columns
     *
     * @return string
     */
    public function fromTable(
        $table,
        $columns = null,
        $class = 'form-control',
        $view = null,
        $reformatted = true,
        $populated = false,
        $idAndTimestamps = false
    ) {
        $formBuild = '';
        $tableColumns = Schema::getColumnListing($table);

        if (is_null($columns)) {
            foreach ($tableColumns as $column) {
                $type = DB::connection()->getDoctrineColumn($table, $column)->getType()->getName();
                $columns[$column] = $type;
            }
        }

        if (!$idAndTimestamps) {
            unset($columns['id']);
            unset($columns['created_at']);
            unset($columns['updated_at']);
        }

        foreach ($columns as $column => $columnConfig) {
            if (is_numeric($column)) {
                $column = $columnConfig;
            }

            $errors = $this->getFormErrors();
            $input = $this->inputMaker->create($column, $columnConfig, $column, $class, $reformatted, $populated);
            $formBuild .= $this->formBuilder($view, $errors, $columnConfig, $column, $input);
        }

        return $formBuild;
    }

    /**
     * Build the form from an array.
     *
     * @param array  $array
     * @param array  $columns
     * @param string $view        A template to use for the rows
     * @param string $class       Default input class
     * @param bool   $populated   Is content populated
     * @param bool   $reformatted Are column names reformatted
     * @param bool   $timestamps  Are the timestamps available?
     *
     * @return string
     */
    public function fromArray(
        $array,
        $columns = null,
        $view = null,
        $class = 'form-control',
        $populated = true,
        $reformatted = false,
        $timestamps = false
    ) {
        $formBuild = '';
        $array = $this->cleanupIdAndTimeStamps($array, $timestamps, false);
        $errors = $this->getFormErrors();

        if (is_null($columns)) {
            $columns = $array;
        }

        foreach ($columns as $column => $columnConfig) {
            if (is_numeric($column)) {
                $column = $columnConfig;
            }
            if ($column === 'id') {
                $columnConfig = ['type' => 'hidden'];
            }

            $input = $this->inputMaker->create($column, $columnConfig, $array, $class, $reformatted, $populated);
            $formBuild .= $this->formBuilder($view, $errors, $columnConfig, $column, $input);
        }

        return $formBuild;
    }

    /**
     * Build the form from the an object.
     *
     * @param object $object      An object to base the form off
     * @param array  $columns     Columns desired and specified
     * @param string $view        A template to use for the rows
     * @param string $class       Default input class
     * @param bool   $populated   Is content populated
     * @param bool   $reformatted Are column names reformatted
     * @param bool   $timestamps  Are the timestamps available?
     *
     * @return string
     */
    public function fromObject(
        $object,
        $columns = null,
        $view = null,
        $class = 'form-control',
        $populated = true,
        $reformatted = false,
        $timestamps = false
    ) {
        $formBuild = '';

        if (is_null($columns)) {
            $columns = array_keys($object['attributes']);
        }

        $columns = $this->cleanupIdAndTimeStamps($columns, $timestamps, false);
        $errors = $this->getFormErrors();

        foreach ($columns as $column => $columnConfig) {
            if (is_numeric($column)) {
                $column = $columnConfig;
            }
            if ($column === 'id') {
                $columnConfig = ['type' => 'hidden'];
            }
            $input = $this->inputMaker->create($column, $columnConfig, $object, $class, $reformatted, $populated);
            $formBuild .= $this->formBuilder($view, $errors, $columnConfig, $column, $input);
        }

        return $formBuild;
    }

    /**
     * Cleanup the ID and TimeStamp columns.
     *
     * @param array $collection
     * @param bool  $timestamps
     * @param bool  $id
     *
     * @return array
     */
    public function cleanupIdAndTimeStamps($collection, $timestamps, $id)
    {
        if (!$timestamps) {
            unset($collection['created_at']);
            unset($collection['updated_at']);
        }

        if (!$id) {
            unset($collection['id']);
        }

        return $collection;
    }

    /**
     * Get form errors.
     *
     * @return mixed
     */
    public function getFormErrors()
    {
        $errors = null;

        if (Session::isStarted()) {
            $errors = Session::get('errors');
        }

        return $errors;
    }

    /**
     * Constructs HTML forms.
     *
     * @param string       $view   View template
     * @param array|object $errors
     * @param array        $field  Array of field values
     * @param string       $column Column name
     * @param string       $input  Input string
     *
     * @return string
     */
    private function formBuilder($view, $errors, $field, $column, $input)
    {
        $formBuild = '';

        if (!empty($errors) && $errors->has($column)) {
            $errorHighlight = ' has-error';
            $errorMessage = $errors->get($column);
        } else {
            $errorHighlight = '';
            $errorMessage = false;
        }

        if (is_null($view)) {
            if (isset($field['type']) && (stristr($field['type'], 'radio') || stristr($field['type'], 'checkbox'))) {
                $formBuild .= '<div class="'.$errorHighlight.'">';
                $formBuild .= '<div class="'.$field['type'].'"><label>'.$input.$this->inputUtilities->cleanString($this->columnLabel($field, $column)).'</label>'.$this->errorMessage($errorMessage).'</div>';
            } elseif (isset($field['type']) && (stristr($field['type'], 'hidden'))) {
                $formBuild .= '<div class="form-group '.$errorHighlight.'">';
                $formBuild .= $input;
            } else {
                $formBuild .= '<div class="form-group '.$errorHighlight.'">';
                $formBuild .= '<label class="control-label" for="'.ucfirst($column).'">'.$this->inputUtilities->cleanString($this->columnLabel($field, $column)).'</label>'.$input.$this->errorMessage($errorMessage);
            }

            $formBuild .= '</div>';
        } else {
            $formBuild .= View::make($view, [
                'labelFor'       => ucfirst($column),
                'label'          => $this->columnLabel($field, $column),
                'input'          => $input,
                'errorMessage'   => $this->errorMessage($errorMessage),
                'errorHighlight' => $errorHighlight,
            ]);
        }

        return $formBuild;
    }

    /**
     * Generate the error message for the input.
     *
     * @param string $message Error message
     *
     * @return string
     */
    private function errorMessage($message)
    {
        if (!$message) {
            $realErrorMessage = '';
        } else {
            $realErrorMessage = '<div><p class="text-danger">'.$message[0].'</p></div>';
        }

        return $realErrorMessage;
    }

    /**
     * Create the column label.
     *
     * @param array  $field  Field from Column Array
     * @param string $column Column name
     *
     * @return string
     */
    private function columnLabel($field, $column)
    {
        if (!is_array($field) && !in_array($field, $this->columnTypes)) {
            return ucfirst($field);
        }

        return (isset($field['alt_name'])) ? $field['alt_name'] : ucfirst($column);
    }

    /**
     * Get Table Columns.
     *
     * @param string $table Table name
     *
     * @return array
     */
    public function getTableColumns($table, $allColumns = false)
    {
        $tableColumns = Schema::getColumnListing($table);

        $tableTypeColumns = [];
        $badColumns = ['id', 'created_at', 'updated_at'];

        if ($allColumns) {
            $badColumns = [];
        }

        foreach ($tableColumns as $column) {
            if (!in_array($column, $badColumns)) {
                $type = DB::connection()->getDoctrineColumn($table, $column)->getType()->getName();
                $tableTypeColumns[$column]['type'] = $type;
            }
        }

        return $tableTypeColumns;
    }
}
