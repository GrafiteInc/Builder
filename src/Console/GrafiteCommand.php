<?php

namespace Grafite\Builder\Console;

use Illuminate\Console\Command;

class GrafiteCommand extends Command
{
    public function starterIsInstalled()
    {
        if (!file_exists(base_path('app/Services/UserService.php'))) {
            $this->line("\n\nPlease perform the starter command:\n");
            $this->info("\n\nphp artisan grafite:starter\n");
            $this->line("\n\nThen once you're able to run the unit tests successfully re-run this command, to bootstrap your app :)\n");

            return false;
        }

        return true;
    }
}
