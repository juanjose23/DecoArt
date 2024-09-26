<?php

namespace App\Filament\Resources\SubcategoriasResource\Pages;

use App\Filament\Resources\SubcategoriasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubcategorias extends ListRecords
{
    protected static string $resource = SubcategoriasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Agregar SubCategoría'),
        ];
    }
}
