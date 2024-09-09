<?php

namespace App\Filament\Resources\MaterialesResource\Pages;

use App\Filament\Resources\MaterialesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateriales extends ListRecords
{
    protected static string $resource = MaterialesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
