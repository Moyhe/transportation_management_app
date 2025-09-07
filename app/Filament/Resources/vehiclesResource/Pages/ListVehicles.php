<?php

namespace App\Filament\Resources\vehiclesResource\Pages;

use App\Filament\Resources\vehiclesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVehicles extends ListRecords
{
    protected static string $resource = vehiclesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
