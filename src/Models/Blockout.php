<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

class Blockout extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'check_in'  => 'datetime:Y-m-d',
        'check_out' => 'datetime:Y-m-d',
    ];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.blockouts');
    }
}
