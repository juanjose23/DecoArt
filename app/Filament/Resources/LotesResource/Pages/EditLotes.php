<?php

namespace App\Filament\Resources\LotesResource\Pages;

use App\Filament\Resources\LotesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLotes extends EditRecord
{
    protected static string $resource = LotesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
