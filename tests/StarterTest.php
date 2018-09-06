<?php

class StarterTest extends TestCase
{
    public function testStarterCommandWithoutStarter()
    {
        $this->artisan('grafite:starter')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', false)
            ->assertExitCode(0);
    }

    public function testStarterCommand()
    {
        $this->artisan('grafite:starter')
            ->expectsQuestion('Are you sure you want to overwrite any files of the same name?', true)
            ->expectsQuestion('Would you like to run the migration?', false)
            ->assertExitCode(0);

        $this->assertTrue(file_exists(base_path('app/Services/UserService.php')));
    }
}
