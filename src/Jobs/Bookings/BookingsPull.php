<?php

namespace IproSync\Jobs\Bookings;

use Angecode\LaravelIproSoft\IproSoftwareFacade;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use IproSync\Ipro\DateTime;
use IproSync\Ipro\PullPagination;
use IproSync\Models\Booking;

class BookingsPull implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PullPagination $pagination;
    protected array $requestParams;

    public function __construct(?PullPagination $pagination = null, array $requestParams = [])
    {
        $this->pagination    = $pagination ?? PullPagination::allPages();
        $this->requestParams = $requestParams;
    }


    public function handle()
    {
        $response = IproSoftwareFacade::searchBookings([
            'query' => $this->pagination->amendQuery($this->requestParams),
        ])->onlySuccessful();

        $items = $response->json('Items');
        $total = $response->json('TotalHits');
        foreach ($items as $item) {
            if (!empty($item['BookingId'])) {
                static::createOrUpdateBooking($item);
            }
        }

        if ($nextPagination = $this->pagination->nextPagination($total)) {
            static::dispatch($nextPagination, $this->requestParams)
                ->onQueue($this->queue);
        }
    }

    public static function createOrUpdateBooking(array $item): ?Booking
    {
        if (isset($item['BookingId'])) {
            $contact = Booking::firstOrNew(['id' => $item['BookingId']], )
                              ->fill([
                                    'external_reservation_id' => !empty($item['ExternalReservationID']) ? (string) $item['ExternalReservationID'] : null,
                                    'property_id'             => !empty($item['PropertyId']) ? (int) $item['PropertyId'] : null,
                                    'contact_id'              => !empty($item['ContactID']) ? (int) $item['ContactID'] : null,
                                    'rep_contact_id'          => !empty($item['RepContactId']) ? (int) $item['RepContactId'] : null,
                                    'booking_status_id'       => !empty($item['BookingStatusId']) ? (int) $item['BookingStatusId'] : null,
                                    'brand_id'                => !empty($item['BrandId']) ? abs((int) $item['BrandId']) : null,
                                    'transaction_id'          => !empty($item['TransactionID']) ? (string) $item['TransactionID'] : null,
                                    'order_time'              => !empty($item['OrderTime']) ? DateTime::createFromMultipleFormats(['Y-m-d', 'Y-m-d\TH:i:s'], Str::before((string) $item['OrderTime'], '.'))->format('Y-m-d') : null,
                                    'modified_time'           => !empty($item['ModifiedTime']) ? DateTime::createFromMultipleFormats(['Y-m-d', 'Y-m-d\TH:i:s'], Str::before((string) $item['ModifiedTime'], '.'))->format('Y-m-d H:i:s') : null,
                                    'check_in'                => !empty($item['CheckIn']) ? Carbon::createFromFormat('Y-m-d', (string) $item['CheckIn'])->format('Y-m-d') : null,
                                    'check_out'               => !empty($item['CheckOut']) ? Carbon::createFromFormat('Y-m-d', (string) $item['CheckOut'])->format('Y-m-d') : null,
                                    'country'                 => !empty($item['Country']) ? (string) $item['Country'] : null,
                                    'currency'                => !empty($item['Currency']) ? (string) $item['Currency'] : null,
                                    'renter_amount'           => !empty($item['RenterAmount']) ? round((float) $item['RenterAmount'], 2) : null,
                                    'booking_fee'             => !empty($item['BookingFee']) ? round((float) $item['BookingFee'], 2) : null,
                                    'booking_fee_vat'         => !empty($item['BookingFeeVAT']) ? round((float) $item['BookingFeeVAT'], 2) : null,
                                    'holiday_extras'          => !empty($item['HolidayExtras']) ? round((float) $item['HolidayExtras'], 2) : null,
                                    'insurance_total'         => !empty($item['InsuranceTotal']) ? round((float) $item['InsuranceTotal'], 2) : null,
                                    'agent_commission'        => !empty($item['AgentCommission']) ? round((float) $item['AgentCommission'], 2) : null,
                                    'discount'                => !empty($item['Discount']) ? round((float) $item['Discount'], 2) : null,
                                    'payment_charges'         => !empty($item['PaymentCharges']) ? round((float) $item['PaymentCharges'], 2) : null,
                                    'compensation'            => !empty($item['Compensation']) ? round((float) $item['Compensation'], 2) : null,
                                    'renter_balance'          => !empty($item['RenterBalance']) ? round((float) $item['RenterBalance'], 2) : null,
                                    'commission'              => !empty($item['Commission']) ? round((float) $item['Commission'], 2) : null,
                                    'commission_vat'          => !empty($item['CommissionVAT']) ? round((float) $item['CommissionVAT'], 2) : null,
                                    'security_deposit'        => !empty($item['SecurityDeposit']) ? round((float) $item['SecurityDeposit'], 2) : null,
                                    'renter_total'            => !empty($item['RenterTotal']) ? round((float) $item['RenterTotal'], 2) : null,
                                    'rate_per_day'            => !empty($item['RatePerDay']) ? round((float) $item['RatePerDay'], 2) : null,
                                    'property_types'          => !empty($item['PropertyTypes']) ? (array) $item['PropertyTypes'] : null,
                                    'status'                  => !empty($item['Status']) ? (string) $item['Status'] : null,
                                    'source'                  => !empty($item['Source']) ? (string) $item['Source'] : null,
                                    'booking_tags'            => !empty($item['BookingTags']) ? (array) $item['BookingTags'] : null,
                                    'customer_name'           => !empty($item['CustomerName']) ? (string) $item['CustomerName'] : null,
                                    'adults'                  => !empty($item['Adults']) ? (int) $item['Adults'] : 0,
                                    'children'                => !empty($item['Children']) ? (int) $item['Children'] : 0,
                                    'infants'                 => !empty($item['Infants']) ? (int) $item['Infants'] : 0,
                                    'pets'                    => !empty($item['Pets']) ? (int) $item['Pets'] : 0,
                                    'guests'                  => !empty($item['Guests']) ? (array) $item['Guests'] : null,
                                    'holiday_extras_ordered'  => !empty($item['HolidayExtrasOrdered']) ? (array) $item['HolidayExtrasOrdered'] : null,
                                    'deposit'                 => !empty($item['Deposit']) ? round((float) $item['Deposit'], 2) : null,
                                    'deposit_due_date'        => !empty($item['DepositDueDate']) ? Carbon::createFromFormat('d/m/Y', (string) $item['DepositDueDate'])->format('Y-m-d') : null,
                                    'balance'                 => !empty($item['Balance']) ? round((float) $item['Balance'], 2) : null,
                                    'balance_due_date'        => !empty($item['BalanceDueDate']) ? Carbon::createFromFormat('d/m/Y', (string) $item['BalanceDueDate'])->format('Y-m-d') : null,
                                    'guest_notes'             => !empty($item['GuestNotes']) ? (string) $item['GuestNotes'] : null,
                                    'house_keeper_notes'      => !empty($item['HouseKeeperNotes']) ? (string) $item['HouseKeeperNotes'] : null,
                                    'internal_notes'          => !empty($item['InternalNotes']) ? (string) $item['InternalNotes'] : null,
                                    'payment_schedules'       => !empty($item['PaymentSchedules']) ? (array) $item['PaymentSchedules'] : null,
                                    'payments'                => !empty($item['Payments']) ? (array) $item['Payments'] : null,
                                    'holiday_notes'           => !empty($item['HolidayNotes']) ? (string) $item['HolidayNotes'] : null,
                                    'arrival_notes'           => !empty($item['ArrivalNotes']) ? (string) $item['ArrivalNotes'] : null,
                                    'departure_notes'         => !empty($item['DepartureNotes']) ? (string) $item['DepartureNotes'] : null,
                                    'bills'                   => !empty($item['Bills']) ? (array) $item['Bills'] : null,
                              ])
                              ->fillPulled();
            $contact->save();

            return $contact;
        }

        return null;
    }
}
