<?php

namespace IproSync\Jobs\Bookings;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Blockout;

class BlockoutsPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $propertyId;
    protected ?Carbon $checkIn  = null;
    protected ?Carbon $checkOut = null;
    protected array $requestParams;

    public function __construct(
        int     $propertyId,
        ?Carbon $checkIn = null,
        ?Carbon $checkOut = null,
        array   $requestParams = []
    ) {
        $this->propertyId    = $propertyId;
        $this->checkIn       = $checkIn;
        $this->checkOut      = $checkOut;
        $this->requestParams = $requestParams;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::getPropertyBlockouts(
            $this->propertyId,
            [
                'query' => array_merge(
                    array_filter([
                        'checkin'  => $this->checkIn?->format('Y-m-d'),
                        'checkout' => $this->checkOut?->format('Y-m-d'),
                    ]),
                    $this->requestParams,
                ),
            ]
        )->onlySuccessful();

        $items = $response->json();
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $item) {
            if (!empty($item['ID'])) {
                static::createOrUpdateBlockout($item);
            }
        }
    }

    public static function createOrUpdateBlockout(array $item): ?Blockout
    {
        if (isset($item['ID'])) {
            $model = Blockout::firstOrNew(['id' => $item['ID']], )
                               ->fill([
                                   'property_id'           => !empty($item['PropertyID']) ? (int) $item['PropertyID'] : null,
                                   'check_in'              => !empty($item['CheckIn']) ? Carbon::createFromFormat('Y-m-d', (string) $item['CheckIn'])->format('Y-m-d') : null,
                                   'check_out'             => !empty($item['CheckOut']) ? Carbon::createFromFormat('Y-m-d', (string) $item['CheckOut'])->format('Y-m-d') : null,
                                   'comments'              => !empty($item['Comments']) ? (string) $item['Comments'] : null,
                                   'imported_from_ical'    => !empty($item['ImportedFromiCal']) ? (string) $item['ImportedFromiCal'] : null,
                                   'imported_from_channel' => !empty($item['ImportedFromChannel']) ? (string) $item['ImportedFromChannel'] : null,
                               ])
                               ->fillPulled();

            $model->save();

            return $model;
        }

        return null;
    }
}
