<?php

namespace IproSync\Jobs\Settings;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Attribute;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class AttributesPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = IproSoftwareFacade::getAttributesList()->onlySuccessful();

        $groups = $response->json();
        if (is_array($groups) && !empty($groups)) {
            foreach ($groups as $items) {
                foreach ($items['Values'] ?? [] as $item) {
                    if (!isset($item['Id'])) {
                        continue;
                    }
                    Attribute::firstOrNew(['id' => $item['Id']], )
                             ->fill([
                                 'name' => $item['Name'],
                                 'type' => $items['Name'],
                             ])
                             ->fillPulled()
                             ->save();
                }
            }
        }
    }
}
