<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \JsonFieldCast\Json\ArrayOfJsonObjectsField $availability
 * @property \JsonFieldCast\Json\SimpleJsonField $day_availability
 */
class Availability extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    protected $primaryKey = 'property_id';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'availability'     => \JsonFieldCast\Casts\ArrayOfJsonObjectsField::class,
        'day_availability' => \JsonFieldCast\Casts\SimpleJsonField::class,
    ];

    public function getTable()
    {
        return config('iprosoftware-sync.tables.availabilities');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }
}
