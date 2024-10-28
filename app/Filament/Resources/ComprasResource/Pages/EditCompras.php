<?php

namespace App\Filament\Resources\ComprasResource\Pages;

use App\Filament\Resources\ComprasResource;
use App\Models\Recepciones;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class EditCompras extends EditRecord
{
    protected static string $resource = ComprasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Actualizar el registro con los datos proporcionados
        $record->update($data);
        
        // Encontrar al usuario relacionado con la compra
        $user = User::find($record->user_id);
        
        // Determinar el título, ícono y tipo de notificación basado en el estado de la compra
        $titulo = '';
        $icon = 'heroicon-o-shopping-bag';
        $tipoNotificacion = 'success'; // Por defecto, notificación exitosa
        if ($data['estado'] == 2) {
            // Buscar si ya existe una recepción para esta compra
            $recepcion = $record->recepcion;
        
            if ($recepcion) {
                // Si existe, actualiza la fecha de recepción y el estado
                $recepcion->update([
                    'fecha_recepcion' => now(),
                    'estado' => 1,
                ]);
            } else {
                // Si no existe, crea una nueva recepción
                $recepcion = $record->recepcion()->create([
                    'fecha_recepcion' => $record->fecha_recepcion,
                    'estado' => 1,
                ]);
            }
        
            // Ahora recorremos los detalles de la compra
            foreach ($record->detalleCompras as $detalle) {
                // Buscar si ya existe el detalle de recepción para este producto en la recepción actual
                $detalleRecepcion = $recepcion->detalleRecepciones()
                    ->where('detalleproducto_id', $detalle->detalleproducto_id)
                    ->first();
        
                if ($detalleRecepcion) {
                    // Si existe, actualiza la cantidad esperada (si es necesario) y cantidad recibida
                    $detalleRecepcion->update([
                        'cantidad_esperada' => $detalle->cantidad,
                        'cantidad_recibida' => $detalleRecepcion->cantidad_recibida, // Mantén el valor existente de cantidad recibida
                    ]);
                } else {
                    // Si no existe, crea el detalle de recepción
                    $recepcion->detalleRecepciones()->create([
                        'detalleproducto_id' => $detalle->detalleproducto_id,
                        'cantidad_esperada' => $detalle->cantidad,
                        'cantidad_recibida' => 0,
                    ]);
                }
            }
        }
        
        switch ($data['estado']) {
            case 2:
                $titulo = 'Compra Aceptada';
                break;
            case 3:
                $titulo = 'Compra En proceso';
                break;
            case 4:
                $titulo = 'Compra devuelta';
                $tipoNotificacion = 'danger'; // Cambia a "danger"
                break;
            case 5:
                $titulo = 'Compra cancelada';
                $tipoNotificacion = 'danger'; // Cambia a "danger"
                break;
        }
    
        // Obtener al usuario que está realizando la acción (autorizando la compra)
        $responsable = auth()->user();
        
        // Enviar notificación con información sobre quién ha autorizado o realizado la acción
        Notification::make()
            ->title($titulo)
            ->icon($icon)
            ->{$tipoNotificacion}()
            ->body("**La compra al proveedor {$record->proveedor?->nombre} de {$record->detalleCompras->count()} productos fue autorizada por {$responsable->name}.**")
            ->actions([
                Action::make('Ver')
                    ->url(ComprasResource::getUrl('edit', ['record' => $record])),
            ])
            ->sendToDatabase($user);
        
        return $record;

    }
    

}
