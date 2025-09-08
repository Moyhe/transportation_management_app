<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vehicle::with(['drivers', 'company'])->get()->each(function ($vehicle) {

            if ($vehicle->drivers->isEmpty()) {
                return;
            }

            Trip::factory()
                ->count(rand(3, 7))
                ->create([
                    'vehicle_id' => $vehicle->id,
                    'driver_id' => $vehicle->drivers->random()->id,
                    'company_id' => $vehicle->company_id,
                ]);
        });
    }
}
