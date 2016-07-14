<?php

use org\bovigo\vfs\vfsStream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Container\Container as Container;
use Illuminate\Support\Facades\Facade as Facade;
use Yab\Laracogs\Generators\DatabaseGenerator;

class DatabaseGeneratorTest extends AppTest
{
    protected $generator;
    protected $config;

    public function setUp()
    {
        parent::setUp();
        $this->generator = new DatabaseGenerator();
        $this->config = [
            '_path_migrations_' => base_path('database/migrations')
        ];
    }

    public function testCreateMigrationFail()
    {
        $this->setExpectedException('Exception');
        $this->generator->createMigration('alskfdjbajlksbdfl', 'TestTable', 'lkdblkabflabsd');
    }

    public function testCreateMigrationSuccess()
    {
        $this->generator->createMigration($this->config, '', 'TestTable', []);

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 1);

        array_map('unlink', glob(base_path('database/migrations').'/*'));

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 0);
    }

    public function testCreateMigrationSuccessAlternativeLocation()
    {
        $config = [
            '_path_migrations_' => base_path('alternative_migrations_location')
        ];

        $this->generator->createMigration($config, '', 'TestTable', []);

        $this->assertCount(1, glob(base_path('alternative_migrations_location').'/*'));

        array_map('unlink', glob(base_path('alternative_migrations_location').'/*'));

        $this->assertCount(0, glob(base_path('alternative_migrations_location').'/*'));
    }

    public function testCreateSchema()
    {
        $this->generator->createMigration($this->config, '', 'TestTable', []);
        $migrations = glob(base_path('database/migrations').'/*');
        $this->assertEquals(count($migrations), 1);

        $this->generator->createSchema($this->config, '', 'TestTable', [], 'id:increments,name:string');

        $this->assertTrue(strpos(file_get_contents($migrations[0]), 'testtables') > 0);
        $this->assertTrue(strpos(file_get_contents($migrations[0]), "table->increments('id')") > 0);

        array_map('unlink', glob(base_path('database/migrations').'/*'));
    }

    public function testCreateSchemaAlternativeLocation()
    {
        $config = [
            '_path_migrations_' => base_path('alternative_migrations_location')
        ];

        $this->generator->createMigration($config, '', 'TestTable', []);
        $migrations = glob(base_path('alternative_migrations_location').'/*');
        $this->assertCount(1, $migrations);

        $this->generator->createSchema($config, '', 'TestTable', [], 'id:increments,name:string');

        $this->assertContains('testtables', file_get_contents($migrations[0]));
        $this->assertContains('table->increments(\'id\')', file_get_contents($migrations[0]));

        array_map('unlink', glob(base_path('alternative_migrations_location').'/*'));
    }
}
