<?php


use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Database\Factories\DriverFactory;
use Database\Factories\TripFactory;
use Database\Factories\UserFactory;
use Database\Factories\VehicleFactory;

beforeEach(function () {
    $user = UserFactory::new()->create();

    $this->actingAs($user);
});

describe('dashboard', function () {
    test('count active trips', function () {

        TripFactory::new()->start()->end()->count(3)->create();

        $activeTrips = Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->count();


        expect($activeTrips)->toBe(3);
    });


    test('count available drivers', function () {

        $drivers = DriverFactory::new()->count(4)->create();

        $busyDriver = $drivers->first();

        TripFactory::new()->start()->end()->create(
            ['driver_id' => $busyDriver->id]
        );

        $busyDriverIds = Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->pluck('driver_id');

        $availableDrivers = Driver::whereNotIn('id', $busyDriverIds)->count();

        expect($availableDrivers)->toBe(3);
    });


    test('count available vehicles', function () {

        $vehicles = VehicleFactory::new()->count(4)->create();

        $busyVehicle = $vehicles->first();

        TripFactory::new()->start()->end()->create(
            ['vehicle_id' => $busyVehicle->id]
        );

        $busyVehicleIds = Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->pluck('vehicle_id');

        $availableDrivers = Vehicle::whereNotIn('id', $busyVehicleIds)->count();

        expect($availableDrivers)->toBe(3);
    });


    test('count completed trips this month', function () {
        
        TripFactory::new()->start()->end()->count(5)->create();

        $completedTrips = Trip::whereMonth('end_time', now()->month)
            ->whereYear('end_time', now()->year)
            ->count();

        expect($completedTrips)->toBe(5);
    });


});


