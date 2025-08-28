<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use App\Models\Crust;
// use Filament\Forms\Components\Select;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
// use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\RawJs;

class CrustsRelationManager extends RelationManager
{
    protected static string $relationship = 'crusts';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('crust_id')
                    ->relationship('crusts', 'name')
                    ->required()
                    ->label('Pinggiran'),

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
                TextColumn::make('name')->label('Nama Pinggiran'),

                TextColumn::make('pivot.price_increase')
                    ->money('IDR') // tampil rapi sebagai Rp10,000
                    ->label('Harga Tambahan'),
            ])
            ->filters([])
            ->headerActions([
                Action::make('attachCrusts')
                    ->label('Tambah Pinggiran')
                    ->form([
                        CheckboxList::make('crusts')
                            ->label('Pilih Pinggiran')
                            ->options(function (RelationManager $livewire) {
                                $attached = $livewire->ownerRecord->crusts()->pluck('crusts.id')->toArray();

                                return Crust::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable()
                            ->required()
                            ->helperText('Pilih pinggiran yang ingin ditambahkan'),

                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ])
                    ->action(function (array $data, RelationManager $livewire) {
                        // attach semua crust yang dipilih dengan price_increase
                        foreach ($data['crusts'] as $crustId) {
                            $livewire->ownerRecord->crusts()->attach($crustId, [
                                'price_increase' => $data['price_increase'],
                            ]);
                        }
                    })
                // AttachAction::make()
                //     ->form(fn (AttachAction $action): array => [
                //         $action->getRecordSelect()
                //             ->preload()
                //             ->label('Pinggiran')
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
                                ->crusts()
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
