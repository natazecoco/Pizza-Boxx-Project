<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Dough;
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
                    ->label('Jenis Adonan'),
                ToggleColumn::make('is_default')
                    ->default(false)
                    ->label('Bawaan'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('attachDoughs')
                    ->label('Tambah Adonan')
                    ->form([
                        CheckboxList::make('doughs')
                            ->label('Pilih Adonan')
                            ->options(function (RelationManager $livewire) {
                                // Ambil semua adonan yang BELUM ter-attach
                                $attachedDoughIds = $livewire->ownerRecord->doughs->pluck('id')->toArray();
                                return Dough::whereNotIn('id', $attachedDoughIds)->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->required()
                            ->helperText('Pilih adonan yang ingin ditambahkan')
                            ->searchable()
                            ->bulkToggleable(),
                    ])
                    ->action(function (RelationManager $livewire, array $data): void {
                        // Attach multiple doughs to the product
                        foreach ($data['doughs'] as $doughId) {
                            $livewire->ownerRecord->doughs()->attach($doughId, [
                                'is_default' => false
                            ]);
                        }
                    }),
                // AttachAction::make()
                // ->multiple(), // Bisa pilih banyak topping sekaligus
            ])
            ->actions([
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}