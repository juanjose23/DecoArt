<?php

namespace App\Filament\Resources\PrecioProductoResource\Pages;

use App\Filament\Resources\PrecioProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrecioProductos extends ListRecords
{
    protected static string $resource = PrecioProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
