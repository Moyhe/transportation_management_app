<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Company::factory()
            ->count(5)
            ->has(Driver::factory()->count(3))
            ->has(Vehicle::factory()->count(4))
            ->create()
            ->each(function ($company) {
                $drivers = $company->drivers;

                $company->vehicles->each(function ($vehicle) use ($drivers) {
                    $randomDrivers = collect($drivers->random(rand(1, $drivers->count())))
                        ->pluck('id')
                        ->toArray();

                    $vehicle->drivers()->attach($randomDrivers);
                });
            });
    }
}
