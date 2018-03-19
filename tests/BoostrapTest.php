<?php

class BootstrapTest extends TestCase
{
    public function testBootstrapCommandWithoutStarter()
    {
        $status = $this->app['Illuminate\Contracts\Console\Kernel']->handle(
            $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'grafite:bootstrap',
                '--no-interaction' => true
            ]),
            $output = new \Symfony\Component\Console\Output\BufferedOutput
        );

        $this->assertContains('php artisan grafite:starter', $output->fetch());
    }
}
