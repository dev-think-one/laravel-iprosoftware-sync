<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use IproSync\Database\Factories\BookingFactory;
use IproSync\Ipro\Price;

class Booking extends Model
{
    use HasFactory;
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'order_time'             => 'date',
        'modified_time'          => 'datetime',
        'check_in'               => 'datetime:Y-m-d',
        'check_out'              => 'datetime:Y-m-d',
        'deposit_due_date'       => 'datetime:Y-m-d',
        'balance_due_date'       => 'datetime:Y-m-d',
        'property_types'         => 'array',
        'booking_tags'           => 'array',
        'guests'                 => 'array',
        'holiday_extras_ordered' => 'array',
        'payment_schedules'      => 'array',
        'payments'               => 'array',
        'bills'                  => 'array',
    ];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.bookings');
    }

    protected static function newFactory(): BookingFactory
    {
        return BookingFactory::new();
    }


    public function name(): Attribute
    {
        return Attribute::get(fn () => implode(' - ', array_filter([
            $this->check_in?->format(config('iprosoftware-sync.date_format.display')),
            $this->check_out?->format(config('iprosoftware-sync.date_format.display')),
        ])) ?: '-');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('booking_status_id', 3);
    }

    public function formattedPrice(string $attributeName): string
    {
        return Price::format((float)$this->$attributeName, $this->currency);
    }
}
