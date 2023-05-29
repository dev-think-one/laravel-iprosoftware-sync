<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Bookings\AvailabilityPull;
use IproSync\Models\Property;

class AvailabilityPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:availability:pull
     {--property_id= : Pull availability by ipro property id.}
     {--months=60 : the number of months of data to be returned, works only for day_availability.}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro availability';

    public function handle(): int
    {
        $months = (int) $this->option('months');
        if ($months < 0 || $months > 120) {
            $months = null;
        }


        if ($this->option('property_id')) {
            AvailabilityPull::dispatch(
                $this->option('property_id'),
                [],
                array_filter(['months' => $months])
            )->onQueue($this->option('queue'));
        } else {
            Property::query()
                    ->chunk(100, function ($properties) use ($months) {
                        /** @var Property $property */
                        foreach ($properties as $property) {
                            AvailabilityPull::dispatch(
                                $property->getKey(),
                                [],
                                array_filter(['months' => $months])
                            )->onQueue($this->option('queue'));
                        }
                    });
        }

        return 0;
    }
}
