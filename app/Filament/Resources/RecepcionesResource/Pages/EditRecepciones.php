<?php

namespace App\Filament\Resources\RecepcionesResource\Pages;
use App\Enums\CompraStatus;
use App\Filament\Resources\RecepcionesResource;
use App\Models\Inventarios;
use App\Models\LoteDetalle;
use App\Models\Lotes;
use App\Models\Transacciones;
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
        try {
            $record->update($data);

            // Verificar si el estado es "Recepcionada" y actualizar cantidades
            if ($data['estado'] == CompraStatus::Recepcionada->value || $data['estado'] == CompraStatus::EnProceso) {
                $compra = $record->compras;
                $subtotal = 0;

                $lote = Lotes::create([
                    'codigo' => 'Lote-' . strtoupper(uniqid()),
                    'es_caducable' => isset($data['fecha_vencimiento']) && !empty($data['fecha_vencimiento']),
                    'fecha_expiracion' => !empty($data['fecha_vencimiento']) ? $data['fecha_vencimiento'] : null,
                    'estado' => 1
                ]);

                foreach ($compra->detalleCompras as $detalle) {
                    $detalleRecepcion = $record->detalleRecepciones->where('detalleproducto_id', $detalle->detalleproducto_id)->first();

                    if ($detalleRecepcion && $detalleRecepcion->cantidad_recibida > $detalle->cantidad) {
                        $detalle->update(['cantidad' => $detalleRecepcion->cantidad_recibida]);
                        $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
                        $detalle->save();
                    }

                    // Sumar el subtotal del detalle al subtotal de la compra
                    $subtotal += $detalle->subtotal;

                    LoteDetalle::create([
                        'lote_id' => $lote->id,
                        'detalleproducto_id' => $detalle->detalleproducto_id,
                        'cantidad_inicial' => $detalleRecepcion->cantidad_recibida,
                        'cantidad_disponible' => $detalleRecepcion->cantidad_recibida
                    ]);

                    // Actualizar el inventario
                    $inventario = Inventarios::where('detalleproducto_id', $detalle->detalleproducto_id)->first();

                    if ($inventario) {
                        // Actualizar stock actual y disponible
                        $inventario->stock_actual += $detalleRecepcion->cantidad_recibida;
                        $inventario->stock_disponible += $detalleRecepcion->cantidad_recibida;
                        $inventario->save();
                    } else {
                        Inventarios::create([
                            'detalleproducto_id' => $detalle->detalleproducto_id,
                            'stock_actual' => $detalleRecepcion->cantidad_recibida,
                            'stock_minimo' => 0,
                            'stock_maximo' => 100,
                            'stock_disponible' => $detalleRecepcion->cantidad_recibida
                        ]);
                    }

                    // Registrar la transacción de recepción
                    Transacciones::create([
                        'detalleproducto_id' => $detalle->detalleproducto_id,
                        'lote_id' => $lote->id,
                        'tipo' => 'recepcion',
                        'cantidad' => $detalleRecepcion->cantidad_recibida,
                        'descripcion' => 'Recepción de compra ID: ' . $compra->id
                    ]);
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

        } catch (\Exception $e) {
            // Manejo del error
            \Log::error($e->getMessage());
            // También puedes lanzar una excepción o manejar el error según lo necesites
            throw $e;
        }
    }


}