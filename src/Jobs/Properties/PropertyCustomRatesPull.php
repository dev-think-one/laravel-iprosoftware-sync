<?php

namespace IproSync\Jobs\Properties;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\MonthCustomRate;
use IproSync\Models\Property;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class PropertyCustomRatesPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $iproPropertyId;

    public function __construct(int $iproPropertyId)
    {
        $this->iproPropertyId = $iproPropertyId;
    }

    public function handle()
    {
        /** @var Property $property */
        $property = Property::query()->findOrFail($this->iproPropertyId);

        $response = IproSoftwareFacade::getPropertyCustomRates($this->iproPropertyId)->onlySuccessful();

        $items = $response->json('Rates');

        $property->monthsCustomRates()
                 ->whereKeyNot(collect($items)->pluck('Id')->all())
                 ->delete();

        foreach ($items as $item) {
            self::createOrUpdatePropertyCustomRate($property->getKey(), $item);
        }
    }

    public static function createOrUpdatePropertyCustomRate(int $propertyId, array $item): ?MonthCustomRate
    {
        if (isset($item['Id'])) {
            /** @var MonthCustomRate $model */
            $model = MonthCustomRate::firstOrNew(['id' => $item['Id']], )
                                    ->fill([
                                        'property_id'     => $propertyId,
                                        'month'           => Carbon::createFromFormat('Y-m', $item['Month'])->startOfMonth()->format('Y-m-d'),
                                        'notes'           => !empty($item['Notes']) ? (string) $item['Notes'] : null,
                                        'week_price_list' => !empty($item['WeekPriceList']) ? (array) $item['WeekPriceList'] : null,
                                        'group_size'      => !empty($item['GroupSize']) ? (array) $item['GroupSize'] : null,
                                    ])
                                    ->fillPulled();
            $model->save();

            return $model;
        }

        return null;
    }
}
