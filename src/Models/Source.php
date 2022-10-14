<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    public function getTable()
    {
        return config('iprosoftware-sync.tables.sources');
    }
}
