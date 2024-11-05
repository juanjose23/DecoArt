<?php

namespace App\Rules;

use App\Models\Inventarios;
use Illuminate\Contracts\Validation\Rule;

class CantidadRecibidaValida implements Rule
{
    protected $detalleproductoId;

    public function __construct($detalleproductoId)
    {
        $this->detalleproductoId = $detalleproductoId;
    }

    public function passes($attribute, $value)
    {
        $inventario = Inventarios::where('detalleproducto_id', $this->detalleproductoId)->first();

        if ($inventario) {
            return $value <= $inventario->stock_maximo; // Validación contra el stock máximo
        }

        return true; // Si no hay inventario, la validación pasa
    }

    public function message()
    {
        return 'La cantidad recibida no puede ser mayor que el stock máximo disponible.';
    }
}
