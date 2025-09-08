<?php

namespace App\Filament\Resources;

use App\Filament\Resources\vehiclesResource\Pages;
use App\Models\Trip;
use App\Models\Vehicle;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class vehiclesResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $slug = 'vehicles';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-truck';
    protected static string|UnitEnum|null $navigationGroup = 'Admin';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Vehicle Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Vehicle Name')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('company_id')
                                    ->label('Company')
                                    ->relationship('company', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable()
                                    ->placeholder('Select a company'),
                            ]),
                    ])
                    ->columnSpan(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Vehicle Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('company.name')
                    ->label('Company Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('trips_count')
                    ->label('Trips Count')
                    ->counts('trips')
                    ->sortable()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->wrap(),

            ])
            ->filters([
                SelectFilter::make('company')->relationship('company', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicles::route('/create'),
            'edit' => Pages\EditVehicles::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        $busyVehicleIds = Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->pluck('vehicle_id');

        return Vehicle::whereNotIn('id', $busyVehicleIds)->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of available Vehicles';
    }
}
