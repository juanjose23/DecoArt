<?php

namespace App\Filament\Resources\PrecioProductoResource\Pages;

use App\Filament\Resources\PrecioProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\PrecioProducto;
class EditPrecioProducto extends EditRecord
{
    protected static string $resource = PrecioProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Validar que el precio no sea negativo
        if ($data['precio'] < 0) {
           
            return $record;
        }
        $precioActivo = PrecioProducto::where('detalleproducto_id', $record->detalleproducto_id)
        ->where('estado', 1) // Buscar solo precios activos
        ->first();
        if ($precioActivo && $precioActivo->id != $record->id) {
            return $record;
        }
        if ($record->precio != $data['precio']) {
          
            $record->update([
                'fecha_fin'=>now(),
                'estado' => 0, // Cambia el estado del registro actual a 0 (inactivo)
            ]);
            // Crear un nuevo registro con los datos actualizados y estado = 1
            $nuevoRegistro = $record->replicate(); // Clonar el registro actual
            $nuevoRegistro->fill($data); // Actualizar los datos del nuevo registro
            $nuevoRegistro->estado = 1; // Definir el nuevo registro como activo
            $nuevoRegistro->save(); // Guardar el nuevo registro
            // Redirigir después de la actualización
            $this->redirect('/admin/precio-productos');
            return $nuevoRegistro;
        }
    
  
       
        return $record;
    }
    

}
