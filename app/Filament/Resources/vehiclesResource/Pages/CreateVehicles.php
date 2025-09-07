<?php

namespace App\Filament\Resources\vehiclesResource\Pages;

use App\Filament\Resources\vehiclesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVehicles extends CreateRecord
{
    protected static string $resource = vehiclesResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
