<?php

namespace App\Filament\Resources;

use App\Filament\Resources\driversResource\Pages;
use App\Models\Driver;
use App\Models\Trip;
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
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class driversResource extends Resource
{
    protected static ?string $model = Driver::class;

    protected static ?string $slug = 'drivers';

    protected static string|null|BackedEnum $navigationIcon = 'heroicon-o-user-group';
    protected static string|null|UnitEnum $navigationGroup = 'Admin';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Driver Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Driver Name')
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
                    ->columnSpan(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Driver Name')
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
                    ->label('Created Date')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\Listdrivers::route('/'),
            'create' => Pages\CreateDrivers::route('/create'),
            'edit' => Pages\Editdrivers::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getNavigationBadge(): ?string
    {
        $busyDriverIds = Trip::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->pluck('driver_id');

        return Driver::whereNotIn('id', $busyDriverIds)->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of available Drivers';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('company', 'trips');
    }
}
