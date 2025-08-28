<?php

namespace App\Filament\Resources\DoughResource\Pages;

use App\Filament\Resources\DoughResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDough extends EditRecord
{
    protected static string $resource = DoughResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
