<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Contacts\ContactPull;
use IproSync\Jobs\Contacts\ContactsPull;

class ContactsPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:contacts:pull
     {--id= : Pull contact by ipro id.}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro contacts';

    public function handle()
    {
        if ($id = $this->option('id')) {
            ContactPull::dispatch($id)
                ->onQueue($this->option('queue'));
        } else {
            ContactsPull::dispatch()
                ->onQueue($this->option('queue'));
        }

        return 0;
    }
}
