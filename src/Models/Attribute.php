<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.attributes');
    }
}
