<?php

namespace Yab\Laracogs\Services;

use Exception;

/**
* CRUD Validator.
*/
class CrudValidator
{
    /**
     * Validate the Schema.
     *
     * @param  Command $command
     * @return void|Exception
     */
    public function validateSchema($command)
    {
        if ($command->option('schema')) {
            foreach (explode(',', $command->option('schema')) as $column) {
                $columnDefinition = explode(':', $column);
                if (!in_array(camel_case($columnDefinition[1]), $command->columnTypes)) {
                    throw new Exception($columnDefinition[1].' is not in the array of valid column types: '.implode(', ', $command->columnTypes), 1);
                }
            }
        }
    }

    /**
     * Validate the options
     *
     * @param  Command $command
     * @return void|Exception
     */
    public function validateOptions($command)
    {
        if ($command->option('ui') && ! in_array($command->option('ui'), ['bootstrap', 'semantic'])) {
            throw new Exception('The UI you selected is not suppported. It must be: bootstrap or semantic.', 1);
        }

        if ((!is_null($command->option('schema')) && !$command->option('migration')) ||
            (!is_null($command->option('relationships')) && !$command->option('migration'))
        ) {
            throw new Exception('In order to use Schema or Relationships you need to use Migrations', 1);
        }
    }
}
