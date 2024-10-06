<?php

namespace App\Filament\Resources\UserResource\Pages;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListUsers extends ListRecords
{

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            /* Action::make('inWork')
                    ->label('Probando acciones')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (){

                    }),*/

            Actions\CreateAction::make()->label('Agregar Usuario'),
        ];
    }
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'new' => Tab::make(),
            'processing' => Tab::make(),
            'shipped' => Tab::make(),
            'delivered' => Tab::make(),
            'cancelled' => Tab::make(),
        ];
    }
}
