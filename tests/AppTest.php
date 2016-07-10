<?php


class AppTest extends Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app->make('Illuminate\Contracts\Http\Kernel');
    }

    /**
     * getPackageProviders.
     *
     * @param App $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Yab\Laracogs\LaracogsProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Form'       => \Collective\Html\FormFacade::class,
            'HTML'       => \Collective\Html\HtmlFacade::class,
            'FormMaker'  => \Yab\Laracogs\Facades\FormMaker::class,
            'InputMaker' => \Yab\Laracogs\Facades\InputMaker::class,
            'Crypto'     => \Yab\Laracogs\Utilities\Crypto::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/../src/Models/Factories');
        $this->artisan('migrate', [
            '--database' => 'testbench',
            '--realpath' => realpath(__DIR__.'/../src/Migrations'),
        ]);
        $this->artisan('vendor:publish', [
            '--provider' => 'Yab\Quarx\QuarxProvider',
            '--force'    => true,
        ]);
        $this->withoutMiddleware();
        $this->withoutEvents();
    }

    public function testFormMaker()
    {
        $formMaker = $this->app['FormMaker'];
        $this->assertTrue(is_object($formMaker));
    }

    public function testInputMaker()
    {
        $inputMaker = $this->app['InputMaker'];
        $this->assertTrue(is_object($inputMaker));
    }

    public function testCrypto()
    {
        $crypto = $this->app['Crypto'];
        $this->assertTrue(is_object($crypto));
    }
}
