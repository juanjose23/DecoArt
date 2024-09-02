<?php

namespace App\Filament\Resources\SubcategoriasResource\Pages;

use App\Filament\Resources\SubcategoriasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubcategorias extends EditRecord
{
    protected static string $resource = SubcategoriasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
