<?php

namespace App\Filament\Resources;

use App\Filament\Resources\tripsResource\Pages;
use App\Models\Trip;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class tripsResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $slug = 'trips';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-map';
    protected static string|UnitEnum|null $navigationGroup = 'Admin';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Trip Details')
                    ->schema([
                        Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select a company'),

                        Select::make('driver_id')
                            ->label('Driver')
                            ->relationship('driver', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select a driver'),

                        Select::make('vehicle_id')
                            ->label('Vehicle')
                            ->relationship('vehicle', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Select a vehicle'),
                    ])
                    ->columnSpan(1),

                Section::make('Schedule')
                    ->schema([
                        DateTimePicker::make('start_time')
                            ->label('Start Time')
                            ->required(),

                        DateTimePicker::make('end_time')
                            ->label('End Time')
                            ->required(),
                    ])
                    ->columnSpan(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Company Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),


                TextColumn::make('driver.name')
                    ->label('Driver Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('vehicle.name')
                    ->label('Vehicle Name')
                    ->sortable()
                    ->searchable()
                    ->wrap(),

                TextColumn::make('start_time')
                    ->label('Start Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->wrap(),

                TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->wrap(),
            ])
            ->filters([
                Filter::make('active')
                    ->label('Active Trips')
                    ->query(fn($query) => $query->where('start_time', '<=', now())->where('end_time', '>=', now())),
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
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrips::route('/create'),
            'edit' => Pages\EditTrips::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }


    public static function getNavigationBadge(): ?string
    {
        return Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of Active trips';
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['company', 'driver', 'vehicle']);
    }
}
