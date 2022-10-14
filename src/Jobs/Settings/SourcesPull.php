<?php

namespace IproSync\Jobs\Settings;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Source;

class SourcesPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $response = IproSoftwareFacade::getSourcesList()->onlySuccessful();

        $items = $response->json();
        if (is_array($items) && !empty($items)) {
            foreach ($items as $item) {
                if (!isset($item['Id'])) {
                    continue;
                }
                Source::firstOrNew(['id' => $item['Id']], )
                      ->fill(['name' => $item['Name']])
                      ->fillPulled()
                      ->save();
            }
        }
    }
}
