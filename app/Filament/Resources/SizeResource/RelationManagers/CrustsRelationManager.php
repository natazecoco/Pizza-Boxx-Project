<?php

namespace App\Filament\Resources\SizeResource\RelationManagers;

use App\Models\Crust;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
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
                Tables\Columns\TextColumn::make('name')->label('Nama Pinggiran'),

                Tables\Columns\TextColumn::make('pivot.price_increase')
                    ->money('IDR') // tampil rapi sebagai Rp10,000
                    ->label('Harga Tambahan'),
            ])
            ->filters([])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->preload()
                            ->label('Pinggiran')
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
