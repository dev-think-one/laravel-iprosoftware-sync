<?php

namespace IproSync\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *  @property \JsonFieldCast\Json\ArrayOfJsonObjectsField $week_price_list
 *  @property \JsonFieldCast\Json\ArrayOfJsonObjectsField $group_size
 */
class MonthCustomRate extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'month'           => 'date',
        'week_price_list' => \JsonFieldCast\Casts\ArrayOfJsonObjectsField::class,
        'group_size'      => \JsonFieldCast\Casts\ArrayOfJsonObjectsField::class,
    ];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.custom_rates');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function scopeYear(Builder $query, Carbon|string|int $year)
    {
        if (!($year instanceof Carbon)) {
            $year = Carbon::createFromFormat('Y', $year);
        }

        $query
            ->where('month', '>=', $year->startOfYear()->format('Y-m-d'))
            ->where('month', '<=', $year->endOfYear()->format('Y-m-d'));
    }

    public function scopeOrderByDate(Builder $query)
    {
        $query->orderBy('month', 'ASC');
    }
}
