<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'children' => 'array',
    ];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.locations');
    }
}
