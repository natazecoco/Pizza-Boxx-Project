<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Size;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\RawJs;

class SizesRelationManager extends RelationManager
{
    protected static string $relationship = 'sizes';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form default untuk edit pivot
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Ukuran'),

                Tables\Columns\TextColumn::make('price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->headerActions([
                Action::make('attachSizes')
                    ->label('Tambah Ukuran')
                    ->form([
                        CheckboxList::make('sizes')
                            ->label('Pilih Ukuran')
                            ->options(function (RelationManager $livewire) {
                                $attached = $livewire->ownerRecord->sizes()->pluck('sizes.id')->toArray();

                                return Size::query()
                                    ->whereNotIn('id', $attached)
                                    ->pluck('name', 'id');
                            })
                            ->columns(2)
                            ->searchable()
                            ->bulkToggleable()
                            ->required()
                            ->helperText('Pilih ukuran yang ingin ditambahkan'),

                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ])
                   ->action(function (array $data, RelationManager $livewire) {
                       $livewire->ownerRecord->sizes()->attach($data['sizes'], [
                           'price_increase' => $data['price_increase'],
                       ]);
                   })
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
                                ->sizes()
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