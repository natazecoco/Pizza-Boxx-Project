<?php

namespace App\Filament\Resources\DoughResource\Pages;

use App\Filament\Resources\DoughResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDoughs extends ListRecords
{
    protected static string $resource = DoughResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
