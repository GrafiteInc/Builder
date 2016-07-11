<?php

namespace Yab\Laracogs\Console;

use Artisan;
use Exception;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Yab\Laracogs\Utilities\FormMaker;

class TableCrud extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'laracogs:table-crud {table}
        {--api : Creates an API Controller and Routes}
        {--ui= : Select one of bootstrap|semantic for the UI}
        {--serviceOnly : Does not generate a Controller or Routes}
        {--withFacade : Creates a facade that can be bound in your app to access the CRUD service}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a basic CRUD from an existing table';

    /**
     * Generate a CRUD stack.
     *
     * @return mixed
     */
    public function handle()
    {
        $filesystem = new Filesystem();
        $table = (string) $this->argument('table');
        $tableDefintion = $this->tableDefintion($table);

        if (empty($tableDefintion)) {
            throw new Exception("There is no table definition for $table. Are you sure you spelled it correctly? Table names are case sensitive.", 1);
        }

        Artisan::call('laracogs:crud', [
            'table'       => $table,
            '--api'       => $this->option('api'),
            '--ui'        => $this->option('ui'),
            '--serviceOnly' => $this->option('serviceOnly'),
            '--withFacade' => $this->option('withFacade'),
            '--migration' => true,
            '--schema'    => $tableDefintion,
        ]);

        $migrationName = 'create_'.$table.'_table';
        $migrationFiles = $filesystem->allFiles(base_path('database/migrations'));

        foreach ($migrationFiles as $file) {
            if (stristr($file->getBasename(), $migrationName)) {
                $migrationData = file_get_contents($file->getPathname());
                if (stristr($migrationData, 'updated_at')) {
                    $migrationData = str_replace('$table->timestamps();', '', $migrationData);
                }
                file_put_contents($file->getPathname(), $migrationData);
            }
        }

        $this->line("\nYou've generated a CRUD for the table: ".$table);
        $this->line("\n\nYou may wish to add this as your testing database");
        $this->line("'testing' => [ 'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '' ],");
        $this->info("\n\nCRUD for $table is done.");
    }

    /**
     * Table definitions.
     *
     * @param string $table
     *
     * @return string
     */
    private function tableDefintion($table)
    {
        $columnStringArray = [];
        $formMaker = new FormMaker();
        $columns = $formMaker->getTableColumns($table, true);

        foreach ($columns as $key => $column) {
            if ($key === 'id') {
                $column['type'] = 'increments';
            }

            $columnStringArray[] = $key.':'.$this->columnNameCheck($column['type']);
        }

        $columnString = implode(',', $columnStringArray);

        return $columnString;
    }

    /**
     * Corrects a column type for Schema building.
     *
     * @param  string $column
     * @return string
     */
    private function columnNameCheck($column)
    {
        $columnsToAdjust = [
            'datetime' => 'dateTime',
            'smallint' => 'smallInteger',
            'bigint' => 'bigInteger',
            'datetimetz' => 'timestamp',
        ];

        if (isset($columnsToAdjust[$column])) {
            return $columnsToAdjust[$column];
        }

        return $column;
    }
}
