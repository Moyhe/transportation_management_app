<x-filament::page>
    <form wire:submit.prevent="checkAvailability" class="space-y-4 mb-4">
        {{ $this->form }}
        <br>
        <x-filament::button type="submit" class="mt-6">Check Availability</x-filament::button>
    </form>

    @if($availableDrivers->isNotEmpty())
        <x-filament::card class="mt-6">
            <h3 class="text-lg font-bold mb-4">Available Drivers</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($availableDrivers as $driver)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $driver->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::card>
    @else
        <x-filament::card class="mt-6">
            <p class="text-gray-500">No drivers available for the selected period.</p>
        </x-filament::card>
    @endif

    @if($availableVehicles->isNotEmpty())
        <x-filament::card class="mt-6">
            <h3 class="text-lg font-bold mb-4">Available Vehicles</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($availableVehicles as $vehicle)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vehicle->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::card>
    @else
        <x-filament::card class="mt-6">
            <p class="text-gray-500">No vehicles available for the selected period.</p>
        </x-filament::card>
    @endif
</x-filament::page>
