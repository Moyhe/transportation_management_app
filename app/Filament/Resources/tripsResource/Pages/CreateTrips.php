<?php

namespace App\Filament\Resources\tripsResource\Pages;

use App\Filament\Resources\tripsResource;
use App\Models\Trip;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateTrips extends CreateRecord
{
    protected static string $resource = tripsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }


    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $overlapExists = Trip::query()
            ->where(function ($q) use ($data) {
                $q->where('driver_id', $data['driver_id'])
                    ->orWhere('vehicle_id', $data['vehicle_id']);
            })
            ->where(function ($q) use ($data) {
                $q->where('end_time', '>', $data['start_time'])
                    ->where('start_time', '<', $data['end_time']);
            })
            ->when($this->record, fn ($q) => $q->where('id', '!=', $this->record->id))
            ->exists();


        if ($overlapExists) {
            Notification::make()
                ->title('Overlap detected')
                ->body('This driver or vehicle is already booked during this period.')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'driver_id' => 'This driver or vehicle is already booked during this period.',
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
