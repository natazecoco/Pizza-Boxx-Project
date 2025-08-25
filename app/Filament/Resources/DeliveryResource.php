<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryResource\Pages;
use App\Models\Delivery;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeliveryResource extends Resource
{
    protected static ?string $model = Delivery::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $label = 'Deliveries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('order_id')
                    ->relationship('order', 'id')
                    ->label('ID Pesanan'),
                Select::make('user_id')
                    ->relationship('driver', 'name')
                    ->label('Kurir'),
                Select::make('status')
                    ->options([
                        'pending' => 'Tertunda',
                        'on_the_way' => 'Dalam Perjalanan',
                        'delivered' => 'Telah Diantar',
                    ])
                    ->required()
                    ->label('Status Pengiriman'),
                TextInput::make('delivery_fee')
                    ->prefix('Rp')
                    ->numeric()
                    ->label('Biaya Pengiriman'),
                TextInput::make('delivery_address')
                    ->label('Alamat Pengiriman'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_id')
                    ->sortable()
                    ->searchable()
                    ->label('ID Pesanan'),
                TextColumn::make('driver.name')
                    ->label('Kurir'),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status'),
                TextColumn::make('delivery_fee')
                    ->money('IDR')
                    ->label('Biaya'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Dibuat'),
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
            'index' => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'edit' => Pages\EditDelivery::route('/{record}/edit'),
        ];
    }
}