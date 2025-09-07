<?php

namespace App\Filament\Resources\driversResource\Pages;

use App\Filament\Resources\driversResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class Listdrivers extends ListRecords
{
    protected static string $resource = driversResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
