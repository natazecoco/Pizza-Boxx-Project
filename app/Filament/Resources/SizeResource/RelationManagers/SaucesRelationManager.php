<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use App\Models\Sauce;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
// use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\RawJs;

class SaucesRelationManager extends RelationManager
{
    protected static string $relationship = 'sauces';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('price_increase')
                    ->required()
                    ->mask(RawJs::make('$money($input)'))
                    ->stripCharacters(',')
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
                    ->label('Nama Saus'),

                TextColumn::make('pivot.price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->headerActions([
                Action::make('attachSauces')
                    ->label('Tambah Saus')
                    ->form([
                        CheckboxList::make('sauces')
                            ->label('Pilih Saus')
                            ->options(function (RelationManager $livewire) {
                                $attached = $livewire->ownerRecord->sauces()->pluck('sauces.id')->toArray();

                                return \App\Models\Sauce::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable()
                            ->required()
                            ->helperText('Pilih saus yang ingin ditambahkan'),

                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // attach semua topping yang dipilih
                        $livewire->ownerRecord->sauces()->attach($data['sauces'], [
                            'price_increase' => $data['price_increase'],
                        ]);
                    }),
                // AttachAction::make()
                //     ->form(fn (AttachAction $action): array => [
                //         $action->getRecordSelect()
                //             ->preload()
                //             ->label('Saus')
                //             ->required()
                //             ->multiple(), // Bisa pilih banyak topping sekaligus

                //         TextInput::make('price_increase')
                //             ->required()
                //             ->mask(RawJs::make('$money($input)'))
                //             ->stripCharacters(',')
                //             ->numeric()
                //             ->prefix('Rp')
                //             ->label('Harga Tambahan'),
                //     ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('lg')
                    ->form(fn () => [
                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ])
                    ->mountUsing(fn ($record, $form) => $form->fill($record->pivot->toArray()))
                    ->using(fn (Model $record, array $data): Model => tap($record, function ($r) use ($data) {
                        $r->pivot->update($data);
                    })),

                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),

                BulkAction::make('editBulk')
                    ->label('Edit Beberapa')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ])
                    ->action(function (array $data, $records, RelationManager $livewire) {
                        foreach ($records as $record) {
                            $livewire->ownerRecord
                                ->sauces()
                                ->updateExistingPivot($record->id, [
                                    'price_increase' => $data['price_increase'],
                                ]);
                        }

                        // reset pilihan checkbox setelah selesai
                        $livewire->dispatch('deselectAllTableRecords');
                    })
                    ->requiresConfirmation()
                    ->modalWidth('lg')
            ]);
    }
}
