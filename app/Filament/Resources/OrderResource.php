<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\OrderItemsRelationManager;
use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $label = 'Orders';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pelanggan'),
                Select::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Lokasi Cabang'),
                Select::make('type')
                    ->options([
                        'delivery' => 'Pengantaran',
                        'takeaway' => 'Ambil Sendiri',
                    ])
                    ->required()
                    ->label('Tipe Pesanan'),
                Select::make('status')
                    ->options([
                        'received' => 'Diterima',
                        'preparing' => 'Disiapkan',
                        'ready' => 'Siap Diambil/Diantar',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->required()
                    ->label('Status Pesanan'),
                TextInput::make('subtotal')
                    ->prefix('Rp')
                    ->numeric()
                    ->readOnly()
                    ->label('Subtotal'),
                TextInput::make('discount')
                    ->prefix('Rp')
                    ->numeric()
                    ->readOnly()
                    ->label('Diskon'),
                TextInput::make('total')
                    ->prefix('Rp')
                    ->numeric()
                    ->readOnly()
                    ->label('Total'),
                Textarea::make('notes')
                    ->label('Catatan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable()
                    ->label('Pelanggan'),
                TextColumn::make('location.name')
                    ->label('Lokasi'),
                TextColumn::make('type')
                    ->badge()
                    ->label('Tipe'),
                TextColumn::make('status')
                    ->badge()
                    ->label('Status'),
                TextColumn::make('total')
                    ->money('IDR')
                    ->label('Total'),
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
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}