<?php

class SemanticTest extends AppTest
{
    public function testSemanticCommandWithoutStarter()
    {
        $kernel = $this->app['Illuminate\Contracts\Console\Kernel'];
        $status = $kernel->handle(
            $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'laracogs:semantic',
                '--no-interaction' => true
            ]),
            $output = new \Symfony\Component\Console\Output\BufferedOutput
        );

        $this->assertTrue(strpos($output->fetch(), 'php artisan laracogs:starter') > 0);
    }
}
