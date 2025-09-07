<?php

namespace App\Filament\Resources\vehiclesResource\Pages;

use App\Filament\Resources\vehiclesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVehicles extends EditRecord
{
    protected static string $resource = vehiclesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
