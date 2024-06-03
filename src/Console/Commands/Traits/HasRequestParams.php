<?php

namespace IproSync\Console\Commands\Traits;

trait HasRequestParams
{
    protected function getRequestParams(): array
    {
        $requestParams    = [];
        $requestParamsStr = $this->option('request_params');
        if ($requestParamsStr && is_string($requestParamsStr)) {
            parse_str(urldecode($requestParamsStr), $requestParams);
        }

        if(!is_array($requestParams)) {
            return [];
        }

        return $requestParams;
    }
}
