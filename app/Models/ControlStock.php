<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlStock extends Model
{
    use HasFactory;
    protected $table = 'control_stocks';

    const SUFICIENTE = "STOCK SUFICIENTE";
    const REORDEN = "PROXIMO A AGOTARSE";
    const MINIMO = "DEBAJO DEL MINIMO";

    /**
     * Reglas de estados de control
     * cuando la sumatoria del stock de nuevos y usados en una misma bodega del inventario del producto con detalle_id #
     * Por ejemplo: detalle_id #1, hay 100 productos nuevos y 40 usados en la bodega de machala, 
     * entonces se realiza un calculo en base a esa sumatoria y se comprueba si:
     * Si sumatoria es mayor al valor del punto de reorden , el estado debe ser @const SUFICIENTE
     * Si sumatoria es menor al punto de reorden , el estado debe ser @const REORDEN
     * Si sumatoria es menor al punto minimo, el estado debe ser @const MINIMO
     */

    /**
     * Relacion uno a muchos (inversa)
     * Obtener el detalle de producto al que pertenece el control de stock 
     */
    public function detalles_producto()
    {
        return $this->belongsTo(DetallesProducto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Obtener la sucursal al que pertenece el control de stock 
     */
    public function sucursales()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
