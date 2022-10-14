<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Properties\PropertyCustomRatesPull;
use IproSync\Models\Property;

class PropertiesCustomRatesPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:properties-custom-rates:pull
     {--id= : Pull property custom rates by ipro id.}
    ';

    protected $description = 'Pull ipro properties custom rates';

    public function handle()
    {
        if ($id = $this->option('id')) {
            PropertyCustomRatesPull::dispatch($id);
        } else {
            Property::query()
                    ->chunk(100, function ($properties) {
                        /** @var Property $property */
                        foreach ($properties as $property) {
                            PropertyCustomRatesPull::dispatch($property->getKey());
                        }
                    });
        }

        return 0;
    }
}
