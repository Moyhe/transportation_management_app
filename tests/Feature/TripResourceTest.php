<?php

use App\Filament\Resources\tripsResource\Pages\CreateTrips;
use App\Filament\Resources\tripsResource\Pages\EditTrips;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use Database\Factories\CompanyFactory;
use Database\Factories\DriverFactory;
use Database\Factories\TripFactory;
use Database\Factories\UserFactory;
use Database\Factories\VehicleFactory;

beforeEach(function () {
    $user = UserFactory::new()->create();

    $this->actingAs($user);
});


describe('admin panel', function () {

    test('creates a trip with valid start and end times', function () {
        $trip = TripFactory::new()->create();

        expect($trip->start_time)->toBeInstanceOf(DateTime::class)
            ->and($trip->end_time)->toBeInstanceOf(DateTime::class)
            ->and($trip->start_time < $trip->end_time)->toBeTrue();
    });


    test('allows trip if no overlap exists', function () {

        $driver = DriverFactory::new()->create();
        $vehicle = VehicleFactory::new()->create();
        $company = Company::factory()->create();

        Livewire::test(CreateTrips::class)
            ->fillForm([
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'company_id' => $company->id,
                'start_time' => now()->addHour()->addMinutes(30),
                'end_time' => now()->addHours(3),
            ])
            ->call('create')
            ->assertNotNotified()
            ->assertHasNoFormErrors();

    });

    test('prevents creating overlapping trips', function () {
        $driver = DriverFactory::new()->create();
        $vehicle = VehicleFactory::new()->create();
        $company = CompanyFactory::new()->create();


        Livewire::test(CreateTrips::class)
            ->fillForm([
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'company_id' => $company->id,
                'start_time' => now()->addHour()->addMinutes(30),
                'end_time' => now()->addHours(3),
            ])
            ->call('create')
            ->assertNotified();
    });

    test('can edit a trip without conflict', function () {

        $trip = TripFactory::new()->create();

        $newStart = $trip->start_time->addHour();
        $newEnd = $trip->end_time->addHour();

        Livewire::test(EditTrips::class, ['record' => $trip->id])
            ->fillForm([
                'start_time' => $newStart,
                'end_time' => $newEnd,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('trips', [
            'id' => $trip->id,
            'start_time' => $newStart,
        ]);

    });

    test('validates the trip creation form', function (array $data, array $errors) {
        $company = Company::factory()->create();
        $driver = Driver::factory()->create();
        $vehicle = Vehicle::factory()->create();

        Livewire::test(CreateTrips::class)
            ->fillForm([
                'company_id' => $company->id,
                'driver_id' => $driver->id,
                'vehicle_id' => $vehicle->id,
                'start_time' => now(),
                'end_time' => now()->addHours(2),
                ...$data,
            ])
            ->call('create')
            ->assertHasFormErrors($errors)
            ->assertNotified(false)
            ->assertNoRedirect();
    })->with([
        '`company_id` is required' => [['company_id' => null], ['company_id' => 'required']],
        '`driver_id` is required' => [['driver_id' => null], ['driver_id' => 'required']],
        '`vehicle_id` is required' => [['vehicle_id' => null], ['vehicle_id' => 'required']],
        '`start_time` is required' => [['start_time' => null], ['start_time' => 'required']],
        '`end_time` is required' => [['end_time' => null], ['end_time' => 'required']],
    ]);

});
