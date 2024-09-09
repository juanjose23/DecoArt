<?php

namespace App\Filament\Resources\PrecioProductoResource\Pages;

use App\Filament\Resources\PrecioProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrecioProducto extends EditRecord
{
    protected static string $resource = PrecioProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
