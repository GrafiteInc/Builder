<?php

use org\bovigo\vfs\vfsStream;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Container\Container as Container;
use Illuminate\Support\Facades\Facade as Facade;
use Yab\Laracogs\Generators\DatabaseGenerator;

class DatabaseGeneratorTest extends AppTest
{
    protected $generator;

   public function setUp()
    {
        parent::setUp();
        $this->generator = new DatabaseGenerator();
    }

    public function testCreateMigrationFail()
    {
        $this->setExpectedException('Exception');
        $this->generator->createMigration('alskfdjbajlksbdfl', 'TestTable', 'lkdblkabflabsd');
    }

    public function testCreateMigrationSuccess()
    {
        $this->generator->createMigration('', 'TestTable', []);

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 1);

        foreach (glob(base_path('database/migrations').'/*') as $migration) {
            unlink($migration);
        }

        $this->assertEquals(count(glob(base_path('database/migrations').'/*')), 0);
    }

    public function testCreateSchema()
    {
        $this->generator->createMigration('', 'TestTable', []);
        $migrations = glob(base_path('database/migrations').'/*');
        $this->assertEquals(count($migrations), 1);

        $this->generator->createSchema('', 'TestTable', [], 'id:increments,name:string');

        $this->assertTrue(strpos(file_get_contents($migrations[0]), 'testtables') > 0);
        $this->assertTrue(strpos(file_get_contents($migrations[0]), "table->increments('id')") > 0);

        foreach (glob(base_path('database/migrations').'/*') as $migration) {
            unlink($migration);
        }
    }
}
