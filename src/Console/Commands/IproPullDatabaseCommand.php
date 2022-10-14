<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class IproPullDatabaseCommand extends Command
{
    protected $signature = 'iprosoftware-sync:database:pull';

    protected $description = 'Pull ipro fully data';

    public function handle()
    {
        Artisan::call('iprosoftware-sync:settings:pull');
        Artisan::call('iprosoftware-sync:contacts:pull');
        Artisan::call('iprosoftware-sync:properties:pull');
        Artisan::call('iprosoftware-sync:properties-custom-rates:pull');
        Artisan::call('iprosoftware-sync:availability:pull');
        Artisan::call('iprosoftware-sync:blockouts:pull');
        Artisan::call('iprosoftware-sync:bookings:pull');

        return 0;
    }
}
