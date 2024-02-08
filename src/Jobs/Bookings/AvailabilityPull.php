<?php

namespace IproSync\Jobs\Bookings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Availability;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class AvailabilityPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $propertyId;
    protected array $availabilityRequestParams    = [];
    protected array $dayAvailabilityRequestParams = [];

    public function __construct(
        int   $propertyId,
        array $availabilityRequestParams = [],
        array $dayAvailabilityRequestParams = []
    ) {
        $this->propertyId                   = $propertyId;
        $this->availabilityRequestParams    = $availabilityRequestParams;
        $this->dayAvailabilityRequestParams = $dayAvailabilityRequestParams;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::getPropertyAvailability(
            $this->propertyId,
            [
                'query' => $this->availabilityRequestParams,
            ]
        )->onlySuccessful();

        $availabilities = $response->json();
        if (!is_array($availabilities)) {
            $availabilities = [];
        }

        $response = IproSoftwareFacade::getPropertyDayAvailability(
            $this->propertyId,
            [
                'query' => $this->availabilityRequestParams,
            ]
        )->onlySuccessful();

        $dayAvailability = $response->json();
        if (!is_array($dayAvailability)) {
            $dayAvailability = [];
        }

        static::createOrUpdateAvailability($this->propertyId, $availabilities, $dayAvailability);
    }

    public static function createOrUpdateAvailability(int $propertyId, array $availability = [], array $dayAvailability = []): ?Availability
    {
        $model = Availability::firstOrNew(['property_id' => $propertyId], )
                         ->fill([
                             'availability'     => $availability,
                             'day_availability' => $dayAvailability,
                         ])
                         ->fillPulled();

        $model->save();

        return $model;
    }
}
