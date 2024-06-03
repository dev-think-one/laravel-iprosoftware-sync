<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Console\Commands\Traits\HasRequestParams;
use IproSync\Jobs\Contacts\ContactPull;
use IproSync\Jobs\Contacts\ContactsPull;

class ContactsPullCommand extends Command
{
    use HasRequestParams;

    protected $signature = 'iprosoftware-sync:contacts:pull
     {--id= : Pull contact by ipro id.}
     {--request_params= : Send query params (url encoded).}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro contacts';

    public function handle(): int
    {
        if ($id = $this->option('id')) {
            ContactPull::dispatch($id, $this->getRequestParams())
                ->onQueue($this->option('queue'));
        } else {
            ContactsPull::dispatch(null, $this->getRequestParams())
                ->onQueue($this->option('queue'));
        }

        return 0;
    }
}
