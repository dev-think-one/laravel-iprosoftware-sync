<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use IproSync\Database\Factories\BookingFactory;
use IproSync\Ipro\Price;

class Booking extends Model
{
    use HasFactory;
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'order_time'             => 'datetime:Y-m-d',
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
        'renter_amount'          => 'float',
        'holiday_extras'         => 'float',
        'discount'               => 'float',
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

    public function renterRawTotal(): Attribute
    {
        return Attribute::get(fn () => round($this->renter_amount + $this->holiday_extras - $this->discount, 2));
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id', 'id');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('booking_status_id', 3);
    }

    public function formattedPrice(string $attributeName): string
    {
        return Price::format((float)$this->$attributeName, $this->currency);
    }

    public function extractGuestNote(string $key, ?string $default = null, array $separators = [
        ':',
        ' - ',
    ]): ?string
    {
        $key  = rtrim($key);
        $keys = [];
        foreach ($separators as $separator) {
            $keys[] = $key . $separator;
        }
        foreach (preg_split("/((\r?\n)|(\r\n?))/", $this->guest_notes ?? '') as $note) {
            $note = trim($note);
            foreach ($keys as $formattedKey) {
                if (Str::startsWith($note, $formattedKey)) {
                    return trim(Str::after($note, $formattedKey));
                }
            }
        }

        return $default;
    }
}
