<?php

namespace IproSync\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use IproSync\Jobs\Bookings\BlockoutsPull;
use IproSync\Models\Property;

class BlockoutsPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:blockouts:pull
     {--property_id= : Pull blockouts by ipro property id.}
     {--from= : Date from, format Y-m-d}
     {--to= : Date to, format Y-m-d}
    ';

    protected $description = 'Pull ipro blockouts';

    public function handle()
    {
        $from = $this->option('from') ? Carbon::createFromFormat('Y-m-d', $this->option('from')) : null;
        $to   = $this->option('to') ? Carbon::createFromFormat('Y-m-d', $this->option('to')) : null;

        if ($this->option('property_id')) {
            BlockoutsPull::dispatch(
                $this->option('property_id'),
                $from,
                $to,
            );
        } else {
            Property::query()
                    ->chunk(100, function ($properties) use ($from, $to) {
                        /** @var Property $property */
                        foreach ($properties as $property) {
                            BlockoutsPull::dispatch($property->getKey(), $from, $to);
                        }
                    });
        }

        return 0;
    }
}
