<?php

namespace App\Filament\Resources\RecepcionesResource\Pages;

use App\Filament\Resources\RecepcionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecepciones extends ListRecords
{
    protected static string $resource = RecepcionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
