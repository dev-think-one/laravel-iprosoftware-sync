<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Bookings\RecentlyUpdatedPull;

class RecentlyUpdatedBookingsBlockoutsPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:recently-updated-bookings-blockouts:pull
     {--minutes_ago=60 : Last updated since.}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro recently updated bookings blockouts';

    public function handle(): int
    {
        RecentlyUpdatedPull::dispatch((int)$this->option('minutes_ago'))
            ->onQueue($this->option('queue'));

        return 0;
    }
}
