<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Models\Promo;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
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
use Filament\Support\RawJs;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;
    protected static ?string $navigationGroup = 'Manajemen Bisnis';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nama Promo'),
                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Kode Promo'),
                Select::make('type')
                    ->options([
                        'fixed' => 'Nilai Tetap',
                        'percentage' => 'Persentase',
                    ])
                    ->required()
                    ->label('Tipe Diskon'),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->label('Nilai Diskon'),
                TextInput::make('min_order_total')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Minimum Total Pesanan'),
                TextInput::make('usage_limit')
                    ->numeric()
                    ->label('Batas Penggunaan')
                    ->helperText('Kosongkan untuk penggunaan tak terbatas.'),
                DateTimePicker::make('starts_at')
                    ->label('Mulai Berlaku'),
                DateTimePicker::make('expires_at')
                    ->label('Berakhir'),
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
                    ->label('Nama Promo'),
                TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('Kode Promo'),
                TextColumn::make('type')
                    ->sortable()
                    ->label('Tipe'),
                TextColumn::make('value')
                    ->label('Nilai'),
                TextColumn::make('min_order_total')
                    ->money('IDR')
                    ->label('Min. Belanja'),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->label('Mulai'),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->label('Berakhir'),
                TextColumn::make('used_count')
                    ->label('Digunakan'),
                TextColumn::make('usage_limit')
                    ->label('Batas')
                    ->formatStateUsing(fn ($state) => $state ?? 'Tak Terbatas'),
                ToggleColumn::make('is_active')
                    ->label('Aktif'),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}