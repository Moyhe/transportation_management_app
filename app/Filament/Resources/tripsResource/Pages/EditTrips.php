<?php

namespace App\Filament\Resources\tripsResource\Pages;

use App\Filament\Resources\tripsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTrips extends EditRecord
{
    protected static string $resource = tripsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
