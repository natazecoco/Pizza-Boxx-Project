<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductAddonResource\Pages;
use App\Models\ProductAddon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs; // Tambahkan ini
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductAddonResource extends Resource
{
    protected static ?string $model = ProductAddon::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->label('Produk'),
                TextInput::make('name')
                    ->required()
                    ->label('Nama Add-on (e.g., Ekstra Keju)'),
                TextInput::make('price_increase')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Tambahan'),
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Aktif'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->sortable()
                    ->searchable()
                    ->label('Produk'),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label('Nama Add-on'),
                TextColumn::make('price_increase')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga Tambahan'),
                ToggleColumn::make('is_active')
                    ->label('Tersedia'),
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
            'index' => Pages\ListProductAddons::route('/'),
            'create' => Pages\CreateProductAddon::route('/create'),
            'edit' => Pages\EditProductAddon::route('/{record}/edit'),
        ];
    }
}