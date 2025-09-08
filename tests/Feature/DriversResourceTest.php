<?php

use App\Filament\Resources\DriversResource\Pages\CreateDrivers;
use App\Filament\Resources\DriversResource\Pages\EditDrivers;
use App\Models\Company;
use Database\Factories\DriverFactory;
use Database\Factories\UserFactory;

beforeEach(function () {
    $user = UserFactory::new()->create();

    $this->actingAs($user);
});


describe('drivers resource', function () {

    test('can create a driver', function () {
        $company = Company::factory()->create();

        Livewire::test(CreateDrivers::class)
            ->fillForm([
                'name' => 'John Doe',
                'company_id' => $company->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('drivers', [
            'name' => 'John Doe',
            'company_id' => $company->id,
        ]);
    });

    test('can edit a driver', function () {
        $driver = DriverFactory::new()->create();

        $newCompany = Company::factory()->create();

        Livewire::test(EditDrivers::class, ['record' => $driver->id])
            ->fillForm([
                'name' => 'Jane Smith',
                'company_id' => $newCompany->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'name' => 'Jane Smith',
            'company_id' => $newCompany->id,
        ]);
    });


    test('validates the driver creation form', function (array $data, array $errors) {
        $company = Company::factory()->create();

        Livewire::test(CreateDrivers::class)
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
