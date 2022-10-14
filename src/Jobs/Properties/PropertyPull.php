<?php

namespace IproSync\Jobs\Properties;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use IproSync\Models\Property;

class PropertyPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $iproPropertyId;

    public function __construct(int $iproPropertyId)
    {
        $this->iproPropertyId = $iproPropertyId;
    }

    public function handle()
    {
        $response = IproSoftwareFacade::getPropertyAll($this->iproPropertyId)->onlySuccessful();

        $item = $response->json();
        self::createOrUpdateProperty($item);
    }

    public static function createOrUpdateProperty(array $item): ?Property
    {
        $propertyDescription = $item['PropertyDetails'] ?? [];
        if (isset($propertyDescription['Id'])) {
            /** @var Property $model */
            $model = Property::firstOrNew(['id' => $propertyDescription['Id']], )
                             ->fill([
                                 'property_reference'     => !empty($propertyDescription['PropertyReference']) ? (string) $propertyDescription['PropertyReference'] : null,
                                 'owner_company_id'       => !empty($propertyDescription['OwnerCompanyId']) ? (int) $propertyDescription['OwnerCompanyId'] : null,
                                 'owner_contact_id'       => !empty($propertyDescription['OwnerContactId']) ? (int) $propertyDescription['OwnerContactId'] : null,
                                 'name'                   => !empty($propertyDescription['Name']) ? (string) $propertyDescription['Name'] : null,
                                 'property_name'          => !empty($propertyDescription['PropertyName']) ? (string) $propertyDescription['PropertyName'] : null,
                                 'title'                  => !empty($propertyDescription['Title']) ? (string) $propertyDescription['Title'] : null,
                                 'owner_property_name'    => !empty($propertyDescription['OwnerPropertyName']) ? (string) $propertyDescription['OwnerPropertyName'] : null,
                                 'suspended'              => (bool) ($propertyDescription['Suspended'] ?? false),
                                 'withdrawn'              => (bool) ($propertyDescription['Withdrawn'] ?? false),
                                 'hide_on_website'        => (bool) ($propertyDescription['HideOnWebsite'] ?? false),
                                 'disable_online_booking' => (bool) ($propertyDescription['DisableOnlineBooking'] ?? false),
                                 'contract_renewal_date'  => !empty($propertyDescription['ContractRenewalDate']) ? (string) $propertyDescription['ContractRenewalDate'] : null,
                                 'property_website'       => !empty($propertyDescription['PropertyWebsite']) ? (string) $propertyDescription['PropertyWebsite'] : null,
                                 'url'                    => !empty($propertyDescription['Url']) ? (string) $propertyDescription['Url'] : null,
                                 'currency'               => !empty($propertyDescription['Currency']) ? (string) $propertyDescription['Currency'] : null,
                                 'currency_iso'           => !empty($propertyDescription['CurrencyISO']) ? (string) $propertyDescription['CurrencyISO'] : null,
                                 'hide_rates'             => (bool) ($propertyDescription['HideRates'] ?? false),
                                 'min_rate'               => !empty($propertyDescription['MinRate']) ? round((float) $propertyDescription['MinRate'], 2) : null,
                                 'max_rate'               => !empty($propertyDescription['MaxRate']) ? round((float) $propertyDescription['MaxRate'], 2) : null,
                                 'rates_include_vat'      => (bool) ($propertyDescription['RatesIncludeVat'] ?? false),
                                 'commission'             => !empty($propertyDescription['Commission']) ? round((float) $propertyDescription['Commission'], 2) : null,
                                 'breakages_deposit'      => !empty($propertyDescription['BreakagesDeposit']) ? round((float) $propertyDescription['BreakagesDeposit'], 2) : null,
                                 'availability_notes'     => !empty($propertyDescription['AvailabilityNotes']) ? (string) $propertyDescription['AvailabilityNotes'] : null,
                                 'intro'                  => !empty($propertyDescription['Intro']) ? (string) $propertyDescription['Intro'] : null,
                                 'main_description'       => !empty($propertyDescription['MainDescription']) ? (string) $propertyDescription['MainDescription'] : null,
                                 'region_description'     => !empty($propertyDescription['RegionDescription']) ? (string) $propertyDescription['RegionDescription'] : null,
                                 'location_description'   => !empty($propertyDescription['LocationDescription']) ? (string) $propertyDescription['LocationDescription'] : null,
                                 'warnings'               => !empty($propertyDescription['Warnings']) ? (string) $propertyDescription['Warnings'] : null,
                                 'rental_notes_title'     => !empty($propertyDescription['RentalNotesTitle']) ? (string) $propertyDescription['RentalNotesTitle'] : null,
                                 'rental_notes'           => !empty($propertyDescription['RentalNotes']) ? (string) $propertyDescription['RentalNotes'] : null,
                                 'rental_notes_title_1'   => !empty($propertyDescription['RentalNotesTitle1']) ? (string) $propertyDescription['RentalNotesTitle1'] : null,
                                 'rental_notes_1'         => !empty($propertyDescription['RentalNotes1']) ? (string) $propertyDescription['RentalNotes1'] : null,
                                 'virtual_tour_title'     => !empty($propertyDescription['VirtualTour']) ? (string) $propertyDescription['VirtualTour'] : null,
                                 'virtual_tour'           => !empty($propertyDescription['VirtualTourTitle']) ? (string) $propertyDescription['VirtualTourTitle'] : null,
                                 'address'                => !empty($propertyDescription['Address']) ? (string) $propertyDescription['Address'] : null,
                                 'address_2'              => !empty($propertyDescription['Address2']) ? (string) $propertyDescription['Address2'] : null,
                                 'city'                   => !empty($propertyDescription['City']) ? (string) $propertyDescription['City'] : null,
                                 'county'                 => !empty($propertyDescription['County']) ? (string) $propertyDescription['County'] : null,
                                 'postcode'               => !empty($propertyDescription['Postcode']) ? (string) $propertyDescription['Postcode'] : null,
                                 'country'                => !empty($propertyDescription['Country']) ? (string) $propertyDescription['Country'] : null,
                                 'geolocation'            => !empty($propertyDescription['GeoLocation']) ? (string) $propertyDescription['GeoLocation'] : null,
                                 'location'               => !empty($propertyDescription['GeoLocation']) ? (array) $propertyDescription['Location'] : null,
                                 'pros'                   => !empty($propertyDescription['Pros']) ? (string) $propertyDescription['Pros'] : null,
                                 'cons'                   => !empty($propertyDescription['Cons']) ? (string) $propertyDescription['Cons'] : null,
                                 'build_size'             => !empty($propertyDescription['BuildSize']) ? (string) $propertyDescription['BuildSize'] : null,
                                 'plot_size'              => !empty($propertyDescription['PlotSize']) ? (string) $propertyDescription['PlotSize'] : null,
                                 'licence'                => !empty($propertyDescription['Licence']) ? (string) $propertyDescription['Licence'] : null,
                                 'trust_pilot_tag'        => !empty($propertyDescription['TrustPilotTag']) ? (string) $propertyDescription['TrustPilotTag'] : null,
                                 'seo_title'              => !empty($propertyDescription['SEOTitle']) ? (string) $propertyDescription['SEOTitle'] : null,
                                 'seo_keywords'           => !empty($propertyDescription['SEOKeywords']) ? (string) $propertyDescription['SEOKeywords'] : null,
                                 'seo_description'        => !empty($propertyDescription['SEODescription']) ? (string) $propertyDescription['SEODescription'] : null,
                                 'property_attributes'    => !empty($propertyDescription['Attributes']) ? (array) $propertyDescription['Attributes'] : null,
                                 'rooms'                  => !empty($propertyDescription['Rooms']) ? (array) $propertyDescription['Rooms'] : null,
                                 'distances'              => !empty($propertyDescription['Distances']) ? (array) $propertyDescription['Distances'] : null,
                                 'reviews'                => !empty($propertyDescription['Reviews']) ? (array) $propertyDescription['Reviews'] : null,
                                 'assigned_contacts'      => !empty($propertyDescription['AssignedContacts']) ? (array) $propertyDescription['AssignedContacts'] : null,
                                 'floor_plan'             => !empty($propertyDescription['FloorPlan']) ? (string) $propertyDescription['FloorPlan'] : null,
                                 'press'                  => !empty($propertyDescription['Press']) ? (string) $propertyDescription['Press'] : null,
                                 'images'                 => !empty($item['PropertyImages']) ? (array) $item['PropertyImages'] : null,
                                 'rates'                  => !empty($item['PropertyRates']) ? (array) $item['PropertyRates'] : null,
                                 'availabilities'         => !empty($item['PropertyAvailabilities']) ? (array) $item['PropertyAvailabilities'] : null,
                                 'holiday_extras'         => !empty($item['PropertyHolidayExtras']) ? (array) $item['PropertyHolidayExtras'] : null,
                                 'discounts'              => !empty($item['PropertyDiscounts']) ? (array) $item['PropertyDiscounts'] : null,
                             ])
                             ->fillPulled();
            $model->save();

            return $model;
        }

        return null;
    }
}
