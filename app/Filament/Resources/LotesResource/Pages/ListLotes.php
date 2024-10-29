<?php

namespace App\Filament\Resources\LotesResource\Pages;

use App\Filament\Resources\LotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLotes extends ListRecords
{
    protected static string $resource = LotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
}
