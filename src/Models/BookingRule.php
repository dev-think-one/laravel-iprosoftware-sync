<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \JsonFieldCast\Json\SimpleJsonField $rules
 */
class BookingRule extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'rules' => \JsonFieldCast\Casts\SimpleJsonField::class,
    ];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.booking_rules');
    }
}
