<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use App\Models\Dough;
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

class DoughsRelationManager extends RelationManager
{
    protected static string $relationship = 'doughs';
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
                    ->label('Nama Roti'),

                TextColumn::make('pivot.price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->headerActions([
                Action::make('attachDoughs')
                    ->label('Tambah Adonan')
                    ->form([
                        CheckboxList::make('doughs')
                            ->label('Pilih Adonan')
                            ->options(function (RelationManager $livewire) {
                                $attached = $livewire->ownerRecord->doughs()->pluck('doughs.id')->toArray();

                                return Dough::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                                })
                                ->columns(2)
                                ->searchable()
                                ->bulkToggleable()
                                ->required()
                                ->helperText('Pilih adonan yang ingin ditambahkan'),

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
                        $livewire->ownerRecord->doughs()->attach($data['doughs'], [
                            'price_increase' => $data['price_increase'],
                        ]);
                    })
                // AttachAction::make()
                //     ->form(fn (AttachAction $action): array => [
                //         $action->getRecordSelect()
                //             ->preload()
                //             ->label('Roti')
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
                                ->doughs()
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
