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
    protected $signature = 'iprosoftware-sync:settings:pull
     {--queue= : Queue to dispatch job.}
     ';

    protected $description = 'Pull ipro settings';

    public function handle()
    {
        AttributesPull::dispatch()->onQueue($this->option('queue'));
        BookingRulesPull::dispatch()->onQueue($this->option('queue'));
        BookingTagsPull::dispatch()->onQueue($this->option('queue'));
        ContactTypesPull::dispatch()->onQueue($this->option('queue'));
        LocationsPull::dispatch()->onQueue($this->option('queue'));
        SourcesPull::dispatch()->onQueue($this->option('queue'));

        return 0;
    }
}
