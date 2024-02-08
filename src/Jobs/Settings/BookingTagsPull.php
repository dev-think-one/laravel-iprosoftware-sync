<?php

namespace IproSync\Jobs\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\BookingTag;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class BookingTagsPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = IproSoftwareFacade::getBookingTagsList()->onlySuccessful();

        $items = $response->json();
        if (is_array($items) && !empty($items)) {
            foreach ($items as $item) {
                if (!isset($item['Id'])) {
                    continue;
                }
                BookingTag::firstOrNew(['id' => $item['Id']], )
                          ->fill(['name' => $item['Name']])
                          ->fillPulled()
                          ->save();
            }
        }
    }
}
