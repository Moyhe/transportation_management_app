<?php

namespace App\Filament\Pages;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Pages\Page;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;


class Manager extends Page implements HasSchemas
{
    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $title = 'Driver & Vehicle Availability';
    public ?string $start_time = null;
    public ?string $end_time = null;
    public mixed $availableDrivers;
    public mixed $availableVehicles;
    protected string $view = 'filament.pages.manager';

    public function mount(): void
    {
        $this->availableDrivers = collect();
        $this->availableVehicles = collect();
    }


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),

                DateTimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),
            ]);

    }


    public function checkAvailability(): void
    {
        $busyDriverIds = Trip::where(function ($q) {
            $q->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time);
        })
            ->pluck('driver_id');

        $busyVehicleIds = Trip::where(function ($q) {
            $q->where('start_time', '<', $this->end_time)
                ->where('end_time', '>', $this->start_time);
        })
            ->pluck('vehicle_id');

        $this->availableDrivers = Driver::whereNotIn('id', $busyDriverIds)->get();
        $this->availableVehicles = Vehicle::whereNotIn('id', $busyVehicleIds)->get();
    }
}
