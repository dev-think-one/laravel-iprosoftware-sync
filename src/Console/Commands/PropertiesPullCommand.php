<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Properties\PropertiesPull;
use IproSync\Jobs\Properties\PropertyPull;

class PropertiesPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:properties:pull
     {--id= : Pull property by ipro id.}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro properties';

    public function handle(): int
    {
        if ($id = $this->option('id')) {
            PropertyPull::dispatch($id)
                ->onQueue($this->option('queue'));
        } else {
            PropertiesPull::dispatch()
                ->onQueue($this->option('queue'));
        }

        return 0;
    }
}
