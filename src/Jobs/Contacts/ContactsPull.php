<?php

namespace IproSync\Jobs\Contacts;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Ipro\PullPagination;

class ContactsPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PullPagination $pagination;
    protected array $requestParams;

    public function __construct(?PullPagination $pagination = null, array $requestParams = [])
    {
        $this->pagination    = $pagination ?? PullPagination::allPages();
        $this->requestParams = $requestParams;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::searchContacts([
            'query' => $this->pagination->amendQuery($this->requestParams),
        ])->onlySuccessful();

        $items = $response->json('Items');
        $total = $response->json('TotalHits');
        foreach ($items as $item) {
            ContactPull::createOrUpdateContact($item);
        }

        if ($nextPagination = $this->pagination->nextPagination($total)) {
            static::dispatch($nextPagination, $this->requestParams)
                  ->onQueue($this->queue);
        }
    }
}
