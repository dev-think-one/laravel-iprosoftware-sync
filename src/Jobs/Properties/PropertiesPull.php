<?php

namespace IproSync\Jobs\Properties;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Ipro\PullPagination;

class PropertiesPull implements ShouldQueue
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
        $response = IproSoftwareFacade::searchLiteProperties([
            'query' => $this->pagination->amendQuery($this->requestParams),
        ])->onlySuccessful();

        $items = $response->json('Items');
        $total = $response->json('TotalHits');
        foreach ($items as $item) {
            if (!empty($item['Id'])) {
                PropertyPull::dispatch($item['Id']);
            }
        }

        if ($nextPagination = $this->pagination->nextPagination($total)) {
            static::dispatch($nextPagination, $this->requestParams)
                ->onQueue($this->queue);
        }
    }
}
