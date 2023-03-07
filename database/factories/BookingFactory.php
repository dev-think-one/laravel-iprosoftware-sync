<?php

namespace IproSync\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use IproSync\Models\Booking;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        do {
            $id = time() . rand(1, 9999);
        } while ($this->model::query()->whereKey($id)->exists());

        return [
            'id'        => $id . rand(1, 9999),
            'check_in'  => Carbon::now()->addDays(2),
            'check_out' => Carbon::now()->addDays(7),
        ];
    }
}
