<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ActividadRealizada extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;
    protected $table = 'actividades_realizadas';
    protected $fillable = [
        'fecha_hora',
        'actividad',
        'observacion',
        'fotografia',
        'empleado_id',
        'actividable_id',
        'actividable_type'
    ];


    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relación polimorfica
    public function actividable() //actividad => activid + able
    {
        return $this->morphTo();
    }
}
