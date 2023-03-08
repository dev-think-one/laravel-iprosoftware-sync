<?php

namespace IproSync\Tests\Models;

use Carbon\Carbon;
use IproSync\Models\Booking;
use IproSync\Tests\TestCase;

class BookingTest extends TestCase
{
    /** @test */
    public function booking_has_formatted_name()
    {
        $from = Carbon::now()->addDays(2);
        $to   = Carbon::now()->addDays(10);

        $booking = Booking::factory()->create([
            'check_in'  => $from,
            'check_out' => $to,
        ]);

        $this->assertEquals("{$from->format('d/m/y')} - {$to->format('d/m/y')}", $booking->name);
    }

    /** @test */
    public function booking_has_renter_raw_total()
    {
        /** @var Booking $booking */
        $booking = Booking::factory()->create([
            'currency'       => '$',
            'renter_amount'  => '12345.32',
            'holiday_extras' => 8.21,
            'discount'       => 3.00,
        ]);

        $this->assertEquals(round(12345.32 + 8.21 - 3, 2), $booking->renter_raw_total);
        $this->assertEquals(12350.53, $booking->renter_raw_total);
        $this->assertEquals('$12,350.53', $booking->formattedPrice('renter_raw_total'));
    }

    /** @test */
    public function extract_guest_note()
    {
        /** @var Booking $booking */
        $booking = Booking::factory()->create(['guest_notes' => null,]);

        $this->assertNull($booking->extractGuestNote('Ages of children'));
        $this->assertEquals('bar', $booking->extractGuestNote('Ages of children', 'bar'));

        $booking = Booking::factory()->create(['guest_notes' => "Nature of stay: Family Holiday\nAges of children:  6, 2",]);

        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay', 'bar'));
        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay'));
        $this->assertEquals('6, 2', $booking->extractGuestNote('Ages of children', 'bar'));

        $booking = Booking::factory()->create(['guest_notes' => "Nature of stay - Family Holiday\nAges of children:  6, 2",]);

        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay', 'bar'));
        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay'));
        $this->assertEquals('6, 2', $booking->extractGuestNote('Ages of children', 'bar'));

        $booking = Booking::factory()->create(['guest_notes' => "Nature of stay% Family Holiday\nAges of children%  6, 2",]);

        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay', 'bar', ['%']));
        $this->assertEquals('Family Holiday', $booking->extractGuestNote('Nature of stay', separators: ['%']));
        $this->assertEquals('6, 2', $booking->extractGuestNote('Ages of children', 'bar', ['%']));
    }
}
