<?php

namespace App\Filament\Resources\CrustResource\Pages;

use App\Filament\Resources\CrustResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCrust extends EditRecord
{
    protected static string $resource = CrustResource::class;

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
