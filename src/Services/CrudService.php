<?php

namespace Yab\Laracogs\Services;

class CrudService
{
    /**
     * Prepare a string of the table.
     *
     * @param string $table
     *
     * @return string
     */
    public function prepareTableDefinition($table)
    {
        $tableDefintion = '';

        foreach (explode(',', $table) as $column) {
            $columnDefinition = explode(':', $column);
            $tableDefintion .= "\t\t'$columnDefinition[0]',\n";
        }

        return $tableDefintion;
    }

    /**
     * Prepare a table array example.
     *
     * @param string $table
     *
     * @return string
     */
    public function prepareTableExample($table)
    {
        $tableExample = '';

        foreach (explode(',', $table) as $key => $column) {
            $columnDefinition = explode(':', $column);
            $example = $this->createExampleByType($columnDefinition[1]);

            if ($key === 0) {
                $tableExample .= "'$columnDefinition[0]' => '$example',\n";
            } else {
                $tableExample .= "\t\t'$columnDefinition[0]' => '$example',\n";
            }
        }

        return $tableExample;
    }

    /**
     * Prepare a models relationships.
     *
     * @param array $relationships
     *
     * @return string
     */
    public function prepareModelRelationships($relationships)
    {
        $relationshipMethods = '';

        foreach ($relationships as $relation) {
            if (!isset($relation[2])) {
                $relationEnd = explode('\\', $relation[1]);
                $relation[2] = strtolower(end($relationEnd));
            }

            $method = str_singular($relation[2]);

            if (stristr($relation[0], 'many')) {
                $method = str_plural($relation[2]);
            }

            $relationshipMethods .= "\n\tpublic function ".$method.'() {';
            $relationshipMethods .= "\n\t\treturn \$this->$relation[0]($relation[1]::class);";
            $relationshipMethods .= "\n\t}";
        }

        return $relationshipMethods;
    }

    /**
     * Build a table schema.
     *
     * @param array  $config
     * @param string $string
     *
     * @return string
     */
    public function getTableSchema($config, $string)
    {
        if (!empty($config['schema'])) {
            $string = str_replace('// _camel_case_ table data', $this->prepareTableExample($config['schema']), $string);
        }


        return $string;
    }

    /**
     * Create an example by type for table definitions.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function createExampleByType($type)
    {
        $typeArray = [
            'bigIncrements' => 1,
            'increments'    => 1,
            'string'        => 'laravel',
            'boolean'       => 1,
            'binary'        => 'Its a bird, its a plane, no its Superman!',
            'char'          => 'a',
            'ipAddress'     => '192.168.1.1',
            'macAddress'    => 'X1:X2:X3:X4:X5:X6',
            'json'          => json_encode(['json' => 'test']),
            'text'          => 'I am Batman',
            'longText'      => 'I am Batman',
            'mediumText'    => 'I am Batman',
            'dateTime'      => date('Y-m-d h:i:s'),
            'date'          => date('Y-m-d'),
            'time'          => date('h:i:s'),
            'timestamp'     => time(),
            'float'         => 1.1,
            'decimal'       => 1.1,
            'double'        => 1.1,
            'integer'       => 1,
            'bigInteger'    => 1,
            'mediumInteger' => 1,
            'smallInteger'  => 1,
            'tinyInteger'   => 1,
        ];

        if (isset($typeArray[$type])) {
            return $typeArray[$type];
        }

        return 1;
    }

    /**
     * Determine if given template filename is a service only template.
     *
     * @param string $filename
     *
     * @return bool
     */
    public function isServiceTest($filename)
    {
        $allowedTypes = [
            'Repository',
            'Service',
        ];

        foreach ($allowedTypes as $allowedType) {
            if (strpos($filename, $allowedType) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Filter the tests.
     *
     * @param array        $templates
     * @param string|array $serviceOnly
     * @param string|array $apiOnly
     * @param string|array $withApi
     *
     * @return array
     */
    public function filterTestTemplates($templates, $serviceOnly, $apiOnly, $withApi)
    {
        $filteredTemplates = [];

        foreach ($templates as $template) {
            if (!empty($serviceOnly)) {
                if ($this->isServiceTest($template)) {
                    $filteredTemplates[] = $template;
                }
            } elseif (($apiOnly || $withApi) && stristr($template->getBasename(), 'Api')) {
                $filteredTemplates[] = $template;
            } else {
                $filteredTemplates[] = $template;
            }
        }

        return $filteredTemplates;
    }
}
