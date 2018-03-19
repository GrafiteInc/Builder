<?php

class StarterTest extends TestCase
{
    public function testStarterCommandWithoutStarter()
    {
        $status = $this->app['Illuminate\Contracts\Console\Kernel']->handle(
            $input = new \Symfony\Component\Console\Input\ArrayInput([
                'command' => 'grafite:starter',
                '--no-interaction' => true
            ]),
            $output = new \Symfony\Component\Console\Output\BufferedOutput
        );

        $this->assertTrue(strpos($output->fetch(), 'You cancelled the grafite:starter') > 0);
    }
}
