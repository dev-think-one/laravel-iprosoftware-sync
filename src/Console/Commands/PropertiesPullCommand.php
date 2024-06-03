<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Console\Commands\Traits\HasRequestParams;
use IproSync\Jobs\Properties\PropertiesPull;
use IproSync\Jobs\Properties\PropertyPull;

class PropertiesPullCommand extends Command
{
    use HasRequestParams;

    protected $signature = 'iprosoftware-sync:properties:pull
     {--id= : Pull property by ipro id.}
     {--request_params= : Send query params (url encoded).}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro properties';

    public function handle(): int
    {
        if ($id = $this->option('id')) {
            PropertyPull::dispatch($id, $this->getRequestParams())
                ->onQueue($this->option('queue'));
        } else {
            PropertiesPull::dispatch(null, $this->getRequestParams())
                ->onQueue($this->option('queue'));
        }

        return 0;
    }
}
