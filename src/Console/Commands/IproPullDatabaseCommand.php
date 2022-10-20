<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IproPullDatabaseCommand extends Command
{
    protected $signature = 'iprosoftware-sync:database:pull
     {--queue= : Queue to dispatch job.}
     ';

    protected $description = 'Pull ipro fully data';

    public function handle()
    {
        $queue = $this->option('queue');

        Artisan::call('iprosoftware-sync:settings:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:contacts:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:properties:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:properties-custom-rates:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:availability:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:blockouts:pull', array_filter([
            '--queue' => $queue,
        ]));
        Artisan::call('iprosoftware-sync:bookings:pull', array_filter([
            '--queue' => $queue,
        ]));

        return 0;
    }
}
