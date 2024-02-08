<?php

namespace IproSync\Jobs\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Location;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class LocationsPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = IproSoftwareFacade::getLocationsList()->onlySuccessful();

        $groups = $response->json();
        if (is_array($groups) && !empty($groups)) {
            foreach ($groups as $items) {
                foreach ($items['Children'] ?? [] as $item) {
                    if (!isset($item['Id'])) {
                        continue;
                    }
                    Location::firstOrNew(['id' => $item['Id']], )
                            ->fill([
                                'name'     => $item['Name'],
                                'type_id'  => $items['Id'],
                                'type'     => $items['Name'],
                                'children' => $item['Children'] ?? [],
                            ])
                            ->fillPulled()
                            ->save();
                }
            }
        }
    }
}
