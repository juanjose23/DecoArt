<?php

namespace App\Filament\Resources\ComprasResource\Pages;

use App\Filament\Resources\ComprasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
class ListCompras extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ComprasResource::class;
    protected function getHeaderWidgets(): array
    {
        return ComprasResource::getWidgets();
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
