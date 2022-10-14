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
    ';

    protected $description = 'Pull ipro bookings';

    public function handle()
    {
        if ($id = $this->option('id')) {
            BookingsPull::dispatch(null, ['bookingID' => $id]);
        } elseif ($id = $this->option('property_id')) {
            BookingsPull::dispatch(null, ['propertyids' => $id]);
        } elseif ($id = $this->option('property_id')) {
            BookingsPull::dispatch(null, ['propertyids' => $id]);
        } elseif ($this->option('existing_properties')) {
            Property::query()
                    ->chunk(100, function ($properties) {
                        /** @var Property $property */
                        foreach ($properties as $property) {
                            BookingsPull::dispatch(null, ['propertyids' => $property->getKey()]);
                        }
                    });
        } else {
            BookingsPull::dispatch();
        }

        return 0;
    }
}
