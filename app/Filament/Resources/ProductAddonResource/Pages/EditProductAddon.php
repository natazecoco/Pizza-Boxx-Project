<?php

namespace App\Filament\Resources\ProductAddonResource\Pages;

use App\Filament\Resources\ProductAddonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductAddon extends EditRecord
{
    protected static string $resource = ProductAddonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
