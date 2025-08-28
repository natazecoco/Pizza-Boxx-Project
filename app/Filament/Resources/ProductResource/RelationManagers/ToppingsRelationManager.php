<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Topping;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;
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
                    ->default(false)
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
                    ->default(false)
                    ->label('Bawaan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('attachToppings')
                    ->label('Tambah Topping')
                    ->form([
                        CheckboxList::make('toppings')
                            ->label('Pilih Topping')
                            ->options(function (RelationManager $livewire) {
                                // Ambil semua topping yang BELUM ter-attach
                                $attached = $livewire->ownerRecord->toppings()->pluck('toppings.id')->toArray();

                                return Topping::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2) // biar lebih rapi
                            ->searchable()
                            ->bulkToggleable()
                            ->required()
                            ->helperText('Pilih topping yang ingin ditambahkan'),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // attach semua topping yang dipilih
                        foreach ($data['toppings'] as $toppingId) {
                            $livewire->ownerRecord->toppings()->attach($toppingId, [
                                'is_default' => false, // pastikan defaultnya false
                            ]);
                        }
                    }),
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}