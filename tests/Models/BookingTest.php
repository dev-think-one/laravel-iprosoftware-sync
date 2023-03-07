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
}
