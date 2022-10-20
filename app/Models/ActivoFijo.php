<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivoFijo extends Model
{
    use HasFactory;
    protected $table = 'activos_fijos';
    protected $fillable = [
        'cantidad',
        'fecha_desde',
        'fecha_hasta',
        'accion',
        'observacion',
        // 'lugar',
        'detalle_id',
        'empleado_id',
        'sucursal_id',
        'condicion_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const ASIGNACION = 'ASIGNACION'; //cuando se entrega el activo al empleado
    const DEVOLUCION = 'DEVOLUCION'; //cuando devuelve el activo a bodega

    /**
     * Relación uno a muchos(inversa).
     * Uno o muchos activos fijos estan asignados a un empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    /**
     * Relación uno a uno.
     * Un activos es un detalle.
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
    
    /**
     * Relación uno a muchos (inversa).
     * Un activo fijo tiene una condicion.
     */
    public function condicion()
    {
        return $this->belongsTo(Condicion::class);
    }

    /**
     * Relación uno a muchos (inversa).
     * Uno o muchos activos fijos estan en una sucursal.
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
}
