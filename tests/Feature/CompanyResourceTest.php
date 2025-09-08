<?php

use App\Filament\Resources\CompanyResource\Pages\CreateCompany;
use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Models\Company;
use Database\Factories\UserFactory;

beforeEach(function () {
    $user = UserFactory::new()->create();

    $this->actingAs($user);
});
describe('company resource', function () {

    test('can create a company', function () {
        $companyData = [
            'name' => 'Apple Inc.',
        ];

        Livewire::test(CreateCompany::class)
            ->fillForm($companyData)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('companies', [
            'name' => 'Apple Inc.',
        ]);
    });

    test('can edit a company', function () {
        $company = Company::factory()->create([
            'name' => 'Old Name',
        ]);

        Livewire::test(EditCompany::class, ['record' => $company->id])
            ->fillForm([
                'name' => 'New Name',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'New Name',
        ]);
    });

    test('validates the company creation form', function (array $data, array $errors) {
        Livewire::test(CreateCompany::class)
            ->fillForm([
                ...$data,
            ])
            ->call('create')
            ->assertHasFormErrors($errors)
            ->assertNotNotified()
            ->assertNoRedirect();
    })->with([
        '`name` is required' => [['name' => null], ['name' => 'required']],
        '`name` unique' => [
            function () {
                $company = Company::factory()->create();
                return [['name' => $company->name], ['name' => 'unique']];
            }
        ],
    ]);
});
