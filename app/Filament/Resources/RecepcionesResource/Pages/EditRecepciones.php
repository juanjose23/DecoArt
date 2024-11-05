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
            // Actualizar el registro de compra
            $record->update($data);

            if ($this->isRecepcionadaOrEnProceso($data['estado'])) {
                $compra = $record->compras;
                $subtotal = 0;

                $lote = $this->getOrCreateLote($record);

                foreach ($compra->detalleCompras as $detalle) {
                    $subtotal += $this->processDetalle($detalle, $record, $lote);
                }

                // Actualizar subtotal y total en compras
                $this->updateCompraTotals($compra, $subtotal, $data['estado']);
            }

            // Notificación
            $this->sendNotification($record, $data['estado']);

            return $record;

        } catch (\Exception $e) {
            // Manejo del error
            \Log::error($e->getMessage());
            throw $e;
        }
    }

    private function isRecepcionadaOrEnProceso(string $estado): bool
    {
        return in_array($estado, [CompraStatus::Recepcionada->value, CompraStatus::EnProceso]);
    }

    private function getOrCreateLote(Model $record): Lotes
    {
        $detalleRecepcion = $record->detalleRecepciones->first();
        if (!$detalleRecepcion) {
            throw new \Exception("No hay detalles de recepción disponibles.");
        }
    
        $esCaducable = $this->isProductCaducable($record);
        $fechaVencimiento = $detalleRecepcion->fecha_vencimiento;
        $detalleCompra = $record->compras->detalleCompras->first();
    
        // Obtener el detalle producto_id
        $detalleProductoId = $detalleCompra ? $detalleCompra->detalleProductos->producto->id : null;
    
        // Buscar un lote existente que coincida con todos los criterios
        $loteExistente = Lotes::where('es_caducable', $esCaducable)
            ->where('fecha_expiracion', $fechaVencimiento)
            ->whereHas('loteDetalles', function ($query) use ($detalleProductoId) {
                $query->where('detalleproducto_id', $detalleProductoId);
            })
            ->first();
    
        return $loteExistente ?: $this->createNewLote($esCaducable, $fechaVencimiento);
    }
    
    private function isProductCaducable(Model $record): bool
    {
        $detalleCompra = $record->compras->detalleCompras->first();
        return $detalleCompra && $detalleCompra->detalleProductos && $detalleCompra->detalleProductos->producto
            ? $detalleCompra->detalleProductos->producto->caducidad
            : false;
    }

    private function createNewLote(bool $esCaducable, ?string $fechaVencimiento): Lotes
    {
        return Lotes::create([
            'codigo' => 'Lote-' . strtoupper(uniqid()),
            'es_caducable' => $esCaducable,
            'fecha_expiracion' => $esCaducable ? $fechaVencimiento : null,
            'estado' => 1,
        ]);
    }

    private function processDetalle($detalle, Model $record, Lotes $lote): float
    {
        $detalleRecepcion = $record->detalleRecepciones->where('detalleproducto_id', $detalle->detalleproducto_id)->first();
        if (!$detalleRecepcion) {
            return 0; // O manejar el caso según tus necesidades
        }

        if ($detalleRecepcion->cantidad_recibida > $detalle->cantidad) {
            $detalle->update(['cantidad' => $detalleRecepcion->cantidad_recibida]);
            $detalle->subtotal = $detalle->cantidad * $detalle->precio_unitario;
            $detalle->save();
        }

        // Sumar el subtotal del detalle al subtotal de la compra
        $subtotal = $detalle->subtotal;

        $this->createLoteDetalle($lote, $detalle, $detalleRecepcion);
        $this->updateInventario($detalle, $detalleRecepcion);

        // Registrar la transacción de recepción
        $this->registerTransaction($detalle, $lote, $detalleRecepcion);

        return $subtotal;
    }

    private function createLoteDetalle(Lotes $lote, $detalle, $detalleRecepcion): void
    {
        LoteDetalle::create([
            'lote_id' => $lote->id,
            'detalleproducto_id' => $detalle->detalleproducto_id,
            'cantidad_inicial' => $detalleRecepcion->cantidad_recibida,
            'cantidad_disponible' => $detalleRecepcion->cantidad_recibida,
        ]);
    }

    private function updateInventario($detalle, $detalleRecepcion): void
    {
        $inventario = Inventarios::where('detalleproducto_id', $detalle->detalleproducto_id)->first();

        if ($inventario) {
            if ($inventario->stock_maximo >= $detalleRecepcion->cantidad_recibida) {
                $inventario->stock_actual += $detalleRecepcion->cantidad_recibida;
                $inventario->stock_disponible += $detalleRecepcion->cantidad_recibida;
                $inventario->save();
            } else {
                throw new \Exception("Stock máximo no suficiente para el producto: {$detalle->detalleproducto_id}");
            }
        } else {
            Inventarios::create([
                'detalleproducto_id' => $detalle->detalleproducto_id,
                'stock_actual' => $detalleRecepcion->cantidad_recibida,
                'stock_minimo' => 0,
                'stock_maximo' => 100, // Ajusta según sea necesario
                'stock_disponible' => $detalleRecepcion->cantidad_recibida,
            ]);
        }
    }

    private function registerTransaction($detalle, Lotes $lote, $detalleRecepcion): void
    {
        Transacciones::create([
            'detalleproducto_id' => $detalle->detalleproducto_id,
            'lote_id' => $lote->id,
            'tipo' => 'recepcion',
            'cantidad' => $detalleRecepcion->cantidad_recibida,
            'descripcion' => 'Recepción de compra ID: ' . $detalle->detalleproducto_id, // Asegúrate de que este ID sea el correcto
        ]);
    }

    private function updateCompraTotals(Model $compra, float $subtotal, string $estado): void
    {
        $compra->subtotal = $subtotal;
        $compra->total = $subtotal + $compra->costo_envio + $compra->costo_aduana + $compra->iva;
        $compra->estado = $estado;
        $compra->save();
    }

    private function sendNotification(Model $record, string $estado): void
    {
        $user = User::find($record->compras->user_id);
        $titulo = '';
        $icon = 'heroicon-o-shopping-bag';
        $tipoNotificacion = 'success';

        switch ($estado) {
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
    }


}