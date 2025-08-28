<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ToppingsRelationManager extends RelationManager
{
    protected static string $relationship = 'toppings';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('topping_id')
                    ->relationship('toppings', 'name')
                    ->preload() // BARIS INI YANG DITAMBAHKAN
                    ->required()
                    ->label('Isian'),
                Toggle::make('is_default')
                    ->required()
                    ->default(true)
                    ->label('Bawaan')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Isian'),
                ToggleColumn::make('is_default')
                    ->label('Bawaan'),
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