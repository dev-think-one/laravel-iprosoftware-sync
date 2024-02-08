<?php

namespace IproSync\Jobs\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use IproSync\Models\BookingRule;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class BookingRulesPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = IproSoftwareFacade::getBookingRulesList()->onlySuccessful();

        $groups = $response->json();
        if (is_array($groups) && !empty($groups)) {
            foreach ($groups as $groupType => $items) {
                foreach ($items as $item) {
                    if (!isset($item['Id'])) {
                        continue;
                    }
                    BookingRule::firstOrNew(['id' => $item['Id']], )
                               ->fill([
                                   'name'  => $item['Name'],
                                   'type'  => $groupType,
                                   'rules' => Arr::except($item, ['Id', 'Name']),
                               ])
                               ->fillPulled()
                               ->save();
                }
            }
        }
    }
}
