<?php

namespace App\Filament\Resources\RecepcionesResource\Pages;
use App\Enums\CompraStatus;
use App\Filament\Resources\RecepcionesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
class EditRecepciones extends EditRecord
{
    protected static string $resource = RecepcionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        // Verificar si el estado es "Recepcionada" y actualizar cantidades
        if ($data['estado'] == CompraStatus::Recepcionada->value || $data['estado'] == CompraStatus::EnProceso) {
            $compra = $record->compras;
            $subtotal = 0;

            foreach ($compra->detalleCompras as $detalle) {
                $detalleRecepcion = $record->detalleRecepciones->where('detalleproducto_id', $detalle->detalleproducto_id)->first();

                if ($detalleRecepcion && $detalleRecepcion->cantidad_recibida > $detalle->cantidad) {
                    // Actualizar cantidad en detalle_compras si la cantidad recibida es mayor
                    $detalle->update(['cantidad' => $detalleRecepcion->cantidad_recibida]);

                    // Recalcular el subtotal del detalle
                    $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
                    $detalle->save();
                }

                // Sumar el subtotal del detalle al subtotal de la compra
                $subtotal += $detalle->subtotal;
            }

            // Actualizar subtotal y total en compras
            $compra->subtotal = $subtotal;
            $compra->total = $subtotal + $compra->costo_envio + $compra->costo_aduana + $compra->iva;
            $compra->estado = $data['estado'];
            $compra->save();
        }

        // Notificación
        $user = User::find($record->compras->user_id);
        $titulo = '';
        $icon = 'heroicon-o-shopping-bag';
        $tipoNotificacion = 'success';

        switch ($data['estado']) {
            case CompraStatus::EnProceso->value:
                $titulo = 'Compra En proceso';
                break;
            case CompraStatus::Recepcionada->value:
                $titulo = 'Compra Recepcionada';
                break;
            case CompraStatus::Cancelada->value:
                $titulo = 'Compra Cancelada';
                $tipoNotificacion = 'danger';
                break;
        }

        $responsable = auth()->user();

        Notification::make()
                    ->title($titulo)
                    ->icon($icon)
            ->{$tipoNotificacion}()
                ->body("**La compra al proveedor {$record->compras->proveedor?->nombre} de {$record->compras->detalleCompras->count()} productos fue recepcionada por {$responsable->name}.**")
                ->actions([
                    Action::make('Ver')
                        ->url(RecepcionesResource::getUrl('edit', ['record' => $record])),
                ])
                ->sendToDatabase($user);

        return $record;
    }


}
