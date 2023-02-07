<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ActivoFijo extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use AuditableModel;
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

    private static $whiteListFilter = [
        '*',
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
