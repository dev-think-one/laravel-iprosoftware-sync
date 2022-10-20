<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Bookings\BookingsPull;
use IproSync\Models\Property;

class BookingsPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:bookings:pull
     {--id= : Pull booking by ipro id.}
     {--property_id= : Pull bookings by ipro property id.}
     {--existing_properties : Pull based on all existing properties.}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro bookings';

    public function handle()
    {
        if ($id = $this->option('id')) {
            BookingsPull::dispatch(null, ['bookingID' => $id])
                        ->onQueue($this->option('queue'));
        } elseif ($id = $this->option('property_id')) {
            BookingsPull::dispatch(null, ['propertyids' => $id])
                        ->onQueue($this->option('queue'));
        } elseif ($id = $this->option('property_id')) {
            BookingsPull::dispatch(null, ['propertyids' => $id])
                        ->onQueue($this->option('queue'));
        } elseif ($this->option('existing_properties')) {
            Property::query()
                    ->chunk(100, function ($properties) {
                        /** @var Property $property */
                        foreach ($properties as $property) {
                            BookingsPull::dispatch(null, ['propertyids' => $property->getKey()])
                                        ->onQueue($this->option('queue'));
                        }
                    });
        } else {
            BookingsPull::dispatch();
        }

        return 0;
    }
}
