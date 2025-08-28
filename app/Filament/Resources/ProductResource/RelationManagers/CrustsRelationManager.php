<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Crust;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CrustsRelationManager extends RelationManager
{
    protected static string $relationship = 'crusts';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('crust_id')
                    ->relationship('crust', 'name')
                    ->preload() // BARIS INI YANG DITAMBAHKAN
                    ->required()
                    ->label('Pinggiran'),
                TextInput::make('price_increase')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Tambahan'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pinggiran'),
                TextColumn::make('pivot.price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                ->multiple(), // Bisa pilih banyak topping sekaligus
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}