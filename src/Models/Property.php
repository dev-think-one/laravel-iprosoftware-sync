<?php

namespace IproSync\Models;

use Illuminate\Database\Eloquent\Casts\Attribute as CastAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class Property extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'location'            => 'array',
        'property_attributes' => 'array',
        'rooms'               => 'array',
        'distances'           => 'array',
        'reviews'             => 'array',
        'assigned_contacts'   => 'array',
        'images'              => 'array',
        'rates'               => 'array',
        'availabilities'      => 'array',
        'holiday_extras'      => 'array',
        'discounts'           => 'array',
    ];

    public function getTable()
    {
        return config('iprosoftware-sync.tables.properties');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'owner_contact_id', 'id');
    }

    public function monthsCustomRates(): HasMany
    {
        return $this->hasMany(MonthCustomRate::class, 'property_id', 'id');
    }

    public function blockouts(): HasMany
    {
        return $this->hasMany(Blockout::class, 'property_id', 'id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'property_id', 'id');
    }

    public function availability(): HasOne
    {
        return $this->hasOne(Availability::class, 'property_id', 'id');
    }

    public function featureImageUrl(): CastAttribute
    {
        return CastAttribute::get(function () {
            $images = is_string($this->images) ? json_decode($this->images, true) : $this->images;
            if (is_array($images) && !empty($images)) {
                $image = Arr::first($images);
                if (is_array($image)) {
                    return $image['HostUrl'] ?? null;
                }
            }

            return null;
        });
    }
}
