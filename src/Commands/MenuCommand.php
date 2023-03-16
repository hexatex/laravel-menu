<?php

namespace Hexatex\LaravelMenu\Commands;

use Illuminate\Console\Command;

class MenuCommand extends Command
{
    public $signature = 'menu';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
