<?php

use App\Filament\Resources\VehiclesResource\Pages\CreateVehicles;
use App\Filament\Resources\VehiclesResource\Pages\EditVehicles;
use App\Models\Company;
use Database\Factories\UserFactory;
use Database\Factories\VehicleFactory;

beforeEach(function () {
    $user = UserFactory::new()->create();

    $this->actingAs($user);
});

describe('vehicles resource', function () {

    test('can create a vehicle', function () {

        $company = Company::factory()->create();

        Livewire::test(CreateVehicles::class)
            ->fillForm([
                'name' => 'Toyota',
                'company_id' => $company->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('vehicles', [
            'name' => 'Toyota',
        ]);
    });

    test('can edit a vehicle', function () {
        $vehicle = VehicleFactory::new()->create();

        Livewire::test(EditVehicles::class, ['record' => $vehicle->id])
            ->fillForm([
                'name' => 'Honda',
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('vehicles', [
            'id' => $vehicle->id,
            'name' => 'Honda',
        ]);
    });

    test('validates the vehicles creation form', function (array $data, array $errors) {
        $company = Company::factory()->create();

        Livewire::test(CreateVehicles::class)
            ->fillForm([
                'name' => 'John Doe',
                'company_id' => $company->id,
                ...$data,
            ])
            ->call('create')
            ->assertHasFormErrors($errors)
            ->assertNotNotified()
            ->assertNoRedirect();
    })->with([
        '`name` is required' => [['name' => null], ['name' => 'required']],
        '`company_id` is required' => [['company_id' => null], ['company_id' => 'required']],
    ]);
});
