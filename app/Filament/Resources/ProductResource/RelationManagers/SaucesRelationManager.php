<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Sauce;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
// use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SaucesRelationManager extends RelationManager
{
    protected static string $relationship = 'sauces';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sauce_id')
                    ->relationship('sauces', 'name')
                    ->preload() // BARIS INI YANG DITAMBAHKAN
                    ->required()
                    ->label('Saus'),
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
                    ->label('Nama Saus'),
                ToggleColumn::make('is_default')
                    ->default(false)
                    ->label('Bawaan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('attachSauces')
                    ->label('Tambah Saus')
                    ->form([
                        CheckboxList::make('sauces')
                            ->label('Pilih Saus')
                            ->options(function (RelationManager $livewire) {
                                // Ambil semua saus yang BELUM ter-attach
                                $attached = $livewire->ownerRecord->sauces()->pluck('sauces.id')->toArray();
                                
                                return Sauce::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                            })
                            ->required()
                            ->columns(2)
                            ->helperText('Pilih saus yang ingin ditambahkan')
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // attach semua saus yang dipilih
                        foreach ($data['sauces'] as $sauceId) {
                            $livewire->ownerRecord->sauces()->attach($sauceId, [
                                'is_default' => false, // pastikan defaultnya false
                            ]);
                        }
                    }),
                    
                // AttachAction::make()
                //     ->multiple(), // Bisa pilih banyak topping sekaligus
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}