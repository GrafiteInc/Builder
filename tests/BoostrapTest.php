<?php

class BootstrapTest extends AppTest
{
    public function testBootstrapCommandWithoutStarter()
    {
        $kernel = $this->app['Illuminate\Contracts\Console\Kernel'];
        $status = $kernel->handle(
            $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'laracogs:bootstrap',
                '--no-interaction' => true
            ]),
            $output = new \Symfony\Component\Console\Output\BufferedOutput
        );

        $this->assertTrue(strpos($output->fetch(), 'php artisan laracogs:starter') > 0);
    }
}
