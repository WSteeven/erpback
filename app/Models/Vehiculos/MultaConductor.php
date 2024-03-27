<?php

namespace App\Models\Vehiculos;

use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class MultaConductor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'veh_multas_conductores';
    protected $fillable = [
        'empleado_id',
        'fecha_infraccion',
        'placa',
        'puntos',
        'total',
        'estado',
        'fecha_pago',
        'comentario',
        'descontable',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado'=>'boolean',
        'descontable'=>'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     * Una o varias multas pertenecen a un Conductor.
     */
    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'empleado_id');
    }
    
    /**
     * Relación polimorfica a una notificación.
     * Un pedido puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }
}
