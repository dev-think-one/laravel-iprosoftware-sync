<?php

namespace IproSync\Models;

use Carbon\Carbon;

trait HasPullAt
{
    public function getHasPullAtCastsAttr()
    {
        return [
            'last_pull_at' => 'datetime',
        ];
    }

    public function fillPulled(?Carbon $datetime = null): static
    {
        return $this->fill([
            'last_pull_at' => $datetime ?? Carbon::now(),
        ]);
    }
}
