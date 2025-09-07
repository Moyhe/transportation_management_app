<?php

namespace App\Filament\Resources\driversResource\Pages;

use App\Filament\Resources\driversResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class Editdrivers extends EditRecord
{
    protected static string $resource = driversResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
