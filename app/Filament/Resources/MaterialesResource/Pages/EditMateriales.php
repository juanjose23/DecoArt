<?php

namespace App\Filament\Resources\MaterialesResource\Pages;

use App\Filament\Resources\MaterialesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateriales extends EditRecord
{
    protected static string $resource = MaterialesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
