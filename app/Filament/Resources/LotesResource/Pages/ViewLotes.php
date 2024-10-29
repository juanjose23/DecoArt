<?php

namespace App\Filament\Resources\LotesResource\Pages;

use App\Filament\Resources\LotesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLotes extends ViewRecord
{
    protected static string $resource = LotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
