<?php

namespace App\Models\ComprasProveedores;


use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CalificacionDepartamentoProveedor extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'calificacion_departamento_proveedor';
    protected $fillable = [
        'detalle_departamento_id',
        'criterio_calificacion_id',
        'comentario',
        'peso',
        'puntaje',
        'calificacion',
    ];


    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relacion uno a muchos (inversa).
     * Una calificacion se realiza en base a un departamento calificador de un proveedor.
     */
    public function departamento_proveedor()
    {
        return $this->belongsTo(DetalleDepartamentoProveedor::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una calificacion se realiza en base a un criterio
     */
    public function criterio_calificacion()
    {
        return $this->belongsTo(CriterioCalificacion::class);
    }
}
