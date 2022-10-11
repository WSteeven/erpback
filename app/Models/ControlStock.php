<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlStock extends Model
{
    use HasFactory;
    protected $table = 'control_stocks';
    protected $fillable = [
        'detalle_id',
        'sucursal_id',
        'cliente_id',
        'minimo',
        'reorden',
        'estado',
    ];

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

    public static function controlExistencias($detalle_id, $sucursal_id, $cliente_id)
    {
        $cantidad = 0;
        $elementos = Inventario::where('detalle_id', $detalle_id)
            ->where('sucursal_id', $sucursal_id)
            ->where('cliente_id', $cliente_id)->get();
        foreach ($elementos as $elemento) {
            $cantidad += $elemento->cantidad;
        }
        return $cantidad;
    }

    /**
     * Relacion uno a muchos (inversa)
     * Obtener el detalle de producto al que pertenece el control de stock 
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
    /**
     * Relacion uno a muchos (inversa)
     * Obtener la sucursal al que pertenece el control de stock 
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Obtener el cliente al que pertenece el id de detalle que se controla el stock
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
