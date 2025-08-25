<?php

namespace App\Filament\Resources\ProductAddonResource\Pages;

use App\Filament\Resources\ProductAddonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductAddons extends ListRecords
{
    protected static string $resource = ProductAddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
