<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Dough;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DoughsRelationManager extends RelationManager
{
    protected static string $relationship = 'doughs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dough_id')
                    ->relationship('doughs', 'name')
                    ->preload() // BARIS INI YANG DITAMBAHKAN
                    ->required()
                    ->label('Roti'),
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
                    ->label('Jenis Adonan'),
                TextColumn::make('pivot.price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make(),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}