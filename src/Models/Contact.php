<?php

namespace IproSync\Models;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use IproSync\Jobs\Contacts\ContactPull;

class Contact extends Model
{
    use HasTraitsWithCasts, HasPullAt;

    public $incrementing = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return config('iprosoftware-sync.tables.contacts');
    }

    public function name(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::get(fn () => implode(' ', array_filter([
            $this->title,
            $this->first_name,
            $this->last_name,
        ])));
    }

    public function updateInToIpro(array $only = [])
    {
        $dataMap = [
            'TypeId'       => 'type_id',
            'BrandId'      => 'brand_id',
            'Title'        => 'title',
            'FirstName'    => 'first_name',
            'LastName'     => 'last_name',
            'Email'        => 'email',
            'EmailAlt'     => 'email_alt',
            'EmailAlt1'    => 'email_alt_1',
            'Telephone'    => 'telephone',
            'TelephoneAlt' => 'telephone_alt',
            'Mobile'       => 'mobile',
            'Address'      => 'address',
            'StreetName'   => 'street_name',
            'TownCity'     => 'town_city',
            'CountyArea'   => 'county_area',
            'Postcode'     => 'postcode',
            'CountryCode'  => 'country_code',
            'CompanyName'  => 'company_name',
            'Comments'     => 'comments',
            'Balance'      => 'balance',
            'Retainer'     => 'Retainer',
            'DoNotMail'    => 'contact_by_post',
            'DoNotEmail'   => 'contact_by_email',
            'DoNotPhone'   => 'contact_by_phone',
            'OnEmailList'  => 'subscribed_to_mailing_list',
        ];

        if (!empty($only)) {
            $dataMap = array_flip(Arr::only(array_flip($dataMap), $only));
        }

        $formParams = [];
        foreach ($dataMap as $iproKey => $dbKey) {
            if (in_array($iproKey, [
                'DoNotMail',
                'DoNotEmail',
                'DoNotPhone',
            ])) {
                $formParams[$iproKey] = !$this->$dbKey;
            } else {
                $formParams[$iproKey] = $this->$dbKey;
            }
        }


        IproSoftwareFacade::createOrUpdateContact([
            'query' => [
                'contactId' => $this->getKey(),
            ],
            'form_params' => $formParams,
        ])->onlySuccessful();

        ContactPull::dispatchSync($this->getKey());

        return $this->refresh();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ContactType::class, 'type_id', 'id');
    }

    public function ownedProperties(): HasMany
    {
        return $this->hasMany(Property::class, 'owner_contact_id', 'id');
    }
}
