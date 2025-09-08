<?php

namespace App\Filament\Resources\driversResource\Pages;

use App\Filament\Resources\driversResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDrivers extends CreateRecord
{
    protected static string $resource = driversResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
