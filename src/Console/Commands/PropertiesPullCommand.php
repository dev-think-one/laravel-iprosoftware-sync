<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Properties\PropertiesPull;
use IproSync\Jobs\Properties\PropertyPull;

class PropertiesPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:properties:pull
     {--id= : Pull property by ipro id.}
    ';

    protected $description = 'Pull ipro properties';

    public function handle()
    {
        if ($id = $this->option('id')) {
            PropertyPull::dispatch($id);
        } else {
            PropertiesPull::dispatch();
        }

        return 0;
    }
}
