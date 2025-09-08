<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 week', '+1 week');
        $end = \Carbon\Carbon::instance($start)->addHours(rand(1, 8));


        return [
            'vehicle_id' => Vehicle::factory(),
            'company_id' => Company::factory(),
            'driver_id' => Driver::factory(),
            'start_time' => $start,
            'end_time' => $end,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function start(): static
    {
        return $this->state(fn(array $attributes) => [
            'start_time' => now()->subHour(),
        ]);
    }

    public function end(): static
    {
        return $this->state(fn(array $attributes) => [
            'end_time' => now()->addHour(),
        ]);
    }
}
