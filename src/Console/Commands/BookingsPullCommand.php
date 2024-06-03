<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Console\Commands\Traits\HasRequestParams;
use IproSync\Jobs\Bookings\BookingsPull;
use IproSync\Models\Property;

class BookingsPullCommand extends Command
{
    use HasRequestParams;

    protected $signature = 'iprosoftware-sync:bookings:pull
     {--id= : Pull booking by ipro id.}
     {--property_id= : Pull bookings by ipro property id.}
     {--existing_properties : Pull based on all existing properties.}
     {--request_params= : Send query params (url encoded).}
     {--queue= : Queue to dispatch job.}
    ';

    protected $description = 'Pull ipro bookings';

    public function handle(): int
    {
        $requestParams = $this->getRequestParams();

        if ($id = $this->option('id')) {
            BookingsPull::dispatch(null, array_merge($requestParams, ['bookingID' => $id]))
                ->onQueue($this->option('queue'));
        } elseif ($id = $this->option('property_id')) {
            BookingsPull::dispatch(null, array_merge($requestParams, ['propertyids' => $id]))
                ->onQueue($this->option('queue'));
        } elseif ($this->option('existing_properties')) {
            Property::query()
                ->chunk(100, function ($properties) use ($requestParams) {
                    /** @var Property $property */
                    foreach ($properties as $property) {
                        BookingsPull::dispatch(null, array_merge($requestParams, ['propertyids' => $property->getKey()]))
                            ->onQueue($this->option('queue'));
                    }
                });
        } else {
            BookingsPull::dispatch(null, $requestParams)
                ->onQueue($this->option('queue'));
        }

        return 0;
    }
}
