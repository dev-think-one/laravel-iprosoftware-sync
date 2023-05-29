<?php

namespace IproSync\Jobs\Bookings;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecentlyUpdatedPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $minutesAgo;

    public function __construct(int $minutesAgo = 60)
    {
        $this->minutesAgo = $minutesAgo;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::getPropertyDayAvailabilityCheck([
            'query' => [
                'lastUpdated' => $this->minutesAgo,
            ],
        ])->onlySuccessful();

        $changedProperties = $response->json('PropertyIDs');

        if(is_array($changedProperties)) {
            foreach ($changedProperties as $changedPropertyId) {
                if(!$changedPropertyId || !is_numeric($changedPropertyId)) {
                    continue;
                }

                BlockoutsPull::dispatch($changedPropertyId)
                    ->onQueue($this->queue);

                BookingsPull::dispatch(null, ['propertyids' => $changedPropertyId])
                    ->onQueue($this->queue);
            }
        }
    }
}
