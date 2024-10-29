<?php

namespace App\Filament\Resources\InventariosResource\Pages;

use App\Filament\Resources\InventariosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventarios extends EditRecord
{
    protected static string $resource = InventariosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
