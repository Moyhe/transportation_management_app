<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Statistics extends StatsOverviewWidget
{
    protected ?string $heading = 'transportation statistics';

    protected function getStats(): array
    {
        $activeTrips = Cache::Remember('active_trips_count', now()->addMinutes(5), function () {
            return Trip::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->count();
        });

        $completedTrips = Cache::Remember('completed_trips_count', now()->addMinutes(5), function () {
            return Trip::whereMonth('end_time', Carbon::now()->month)
                ->whereYear('end_time', Carbon::now()->year)
                ->count();
        });

        $availableDrivers = Cache::remember('available_drivers_count', now()->addMinutes(5), function () {
            $busyDriverIds = Trip::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->pluck('driver_id');

            return Driver::whereNotIn('id', $busyDriverIds)->count();
        });

        $availableVehicles = Cache::remember('available_vehicles_count', now()->addMinutes(5), function () {
            $busyVehicleIds = Trip::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->pluck('vehicle_id');

            return Vehicle::whereNotIn('id', $busyVehicleIds)->count();
        });

        return [
            Stat::make('Active Trips', $activeTrips)
                ->description('Trips happening right now')
                ->color('success'),

            Stat::make('Available Drivers', $availableDrivers)
                ->description('Drivers not on a trip')
                ->color('info'),

            Stat::make('Available Vehicles', $availableVehicles)
                ->description('Vehicles not in use')
                ->color('info'),

            Stat::make('Completed Trips This Month', $completedTrips)
                ->description('Trips finished in '.now()->format('F'))
                ->color('primary'),
        ];
    }

}
