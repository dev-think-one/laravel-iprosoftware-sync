<?php

namespace IproSync\Console\Commands;

use Illuminate\Console\Command;
use IproSync\Jobs\Settings\AttributesPull;
use IproSync\Jobs\Settings\BookingRulesPull;
use IproSync\Jobs\Settings\BookingTagsPull;
use IproSync\Jobs\Settings\ContactTypesPull;
use IproSync\Jobs\Settings\LocationsPull;
use IproSync\Jobs\Settings\SourcesPull;

class SettingsPullCommand extends Command
{
    protected $signature = 'iprosoftware-sync:settings:pull';

    protected $description = 'Pull ipro settings';

    public function handle()
    {
        AttributesPull::dispatch();
        BookingRulesPull::dispatch();
        BookingTagsPull::dispatch();
        ContactTypesPull::dispatch();
        LocationsPull::dispatch();
        SourcesPull::dispatch();

        return 0;
    }
}
