<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\RawJs;

class ToppingsRelationManager extends RelationManager
{
    protected static string $relationship = 'toppings';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form->schema([
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
                    ->label('Nama Isian'),

                TextColumn::make('pivot.price_increase')
                    ->money('IDR')
                    ->label('Harga Tambahan'),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->preload()
                            ->label('Isian')
                            ->required(),

                        TextInput::make('price_increase')
                            ->required()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->prefix('Rp')
                            ->label('Harga Tambahan'),
                    ]),
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
            ]);
    }
}
