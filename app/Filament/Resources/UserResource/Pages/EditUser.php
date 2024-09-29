<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Si el toggle 'generar' está activo
        if ($data['generar']) {
            // Buscar el usuario basado en el ID del registro
            $Usuario = User::find($record->id);

            if ($Usuario && $Usuario->estado == 1) {
                
                $Usuario->update([
                    'password' => $data['password'],
                    'estado' => 2, 
                ]);
                $this->redirect('/admin/auth/users');
              
                return $Usuario;
            }
        }

     
        return $record;
    }

}
