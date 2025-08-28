<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LocationResource\Pages;
use App\Models\Location;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;
    protected static ?string $navigationGroup = 'Manajemen Bisnis';
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $label = 'Branch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Nama Cabang'),
                RichEditor::make('address')
                    ->required()
                    ->columnSpanFull()
                    ->label('Alamat Lengkap'),
                TextInput::make('phone_number')
                    ->label('Nomor Telepon'),
                TextInput::make('operational_hours')
                    ->label('Jam Operasional')
                    ->helperText('Contoh: 09.00 - 22.00'),
                TextInput::make('latitude')
                    ->numeric()
                    ->label('Latitude'),
                TextInput::make('longitude')
                    ->numeric()
                    ->label('Longitude'),
                TextInput::make('delivery_radius')
                    ->required()
                    ->numeric()
                    ->default(5)
                    ->label('Radius Pengantaran (km)'),
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Aktif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Cabang'),
                TextColumn::make('address')
                    ->limit(50)
                    ->label('Alamat'),
                TextColumn::make('phone_number')
                    ->label('Nomor Telepon'),
                TextColumn::make('operational_hours')
                    ->label('Jam Operasional'),
                TextColumn::make('delivery_radius')
                    ->label('Radius (km)'),
                ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLocations::route('/'),
            'create' => Pages\CreateLocation::route('/create'),
            'edit' => Pages\EditLocation::route('/{record}/edit'),
        ];
    }
}