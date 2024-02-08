<?php

namespace IproSync\Jobs\Contacts;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Contact;
use LaravelIproSoftwareApi\IproSoftwareFacade;

class ContactPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $iproContactId;

    public function __construct(int $iproContactId)
    {
        $this->iproContactId = $iproContactId;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::getContact($this->iproContactId)->onlySuccessful();

        $item = $response->json();
        self::createOrUpdateContact($item);
    }

    public static function createOrUpdateContact(array $item): ?Contact
    {
        if (isset($item['Id'])) {
            $contact = Contact::firstOrNew(['id' => $item['Id']], )
                              ->fill([
                                  'type_id'                    => !empty($item['TypeId']) ? (int) $item['TypeId'] : null,
                                  'brand_id'                   => !empty($item['BrandId']) ? (int) $item['BrandId'] : null,
                                  'company_id'                 => !empty($item['CompanyId']) ? (int) $item['CompanyId'] : null,
                                  'company_name'               => !empty($item['CompanyName']) ? (string) $item['CompanyName'] : null,
                                  'title'                      => !empty($item['Title']) ? (string) $item['Title'] : null,
                                  'first_name'                 => !empty($item['FirstName']) ? (string) $item['FirstName'] : null,
                                  'last_name'                  => !empty($item['LastName']) ? (string) $item['LastName'] : null,
                                  'email'                      => !empty($item['Email']) ? (string) $item['Email'] : null,
                                  'email_alt'                  => !empty($item['EmailAlt']) ? (string) $item['EmailAlt'] : null,
                                  'email_alt_1'                => !empty($item['EmailAlt1']) ? (string) $item['EmailAlt1'] : null,
                                  'telephone'                  => !empty($item['Telephone']) ? (string) $item['Telephone'] : null,
                                  'telephone_alt'              => !empty($item['TelephoneAlt']) ? (string) $item['TelephoneAlt'] : null,
                                  'mobile'                     => !empty($item['Mobile']) ? (string) $item['Mobile'] : null,
                                  'address'                    => !empty($item['Address']) ? (string) $item['Address'] : null,
                                  'street_name'                => !empty($item['StreetName']) ? (string) $item['StreetName'] : null,
                                  'town_city'                  => !empty($item['TownCity']) ? (string) $item['TownCity'] : null,
                                  'county_area'                => !empty($item['CountyArea']) ? (string) $item['CountyArea'] : null,
                                  'postcode'                   => !empty($item['Postcode']) ? (string) $item['Postcode'] : null,
                                  'country'                    => !empty($item['Country']) ? (string) $item['Country'] : null,
                                  'country_code'               => !empty($item['CountryCode']) ? (string) $item['CountryCode'] : null,
                                  'language'                   => !empty($item['Language']) ? (string) $item['Language'] : null,
                                  'contact_by_post'            => (bool) ($item['ContactByPost'] ?? false),
                                  'contact_by_email'           => (bool) ($item['ContactByEmail'] ?? false),
                                  'contact_by_phone'           => (bool) ($item['ContactByPhone'] ?? false),
                                  'contact_by_sms'             => (bool) ($item['ContactBySms'] ?? false),
                                  'subscribed_to_mailing_list' => (bool) ($item['SubscribedToMailingList'] ?? false),
                                  'comments'                   => !empty($item['Comments']) ? (string) $item['Comments'] : null,
                                  'commission'                 => !empty($item['Commision']) ? round((float) $item['Commision'], 2) : null,
                                  'balance'                    => !empty($item['Balance']) ? round((float) $item['Balance'], 2) : null,
                                  'retainer'                   => !empty($item['Retainer']) ? (string) $item['Retainer'] : null,
                              ])
                              ->fillPulled();
            $contact->save();

            return $contact;
        }

        return null;
    }
}
