<?php

namespace App\Models\Vehiculos;

use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Conductor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'veh_conductores';
    protected $fillable = [
        'empleado_id',
        'identificacion',
        'tipo_licencia',
        'inicio_vigencia',
        'fin_vigencia',
        'puntos',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    protected $primaryKey = 'empleado_id';
    //obtener la llave primaria
    public function getKeyName()
    {
        return 'empleado_id';
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a uno.
     * Un Conductor es un empleado.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id',);
    }

    /**
     * Relación uno a muchos.
     * Un Conductor tiene una o varias multas
     */
    public function multas()
    {
        return $this->hasMany(MultaConductor::class,  'empleado_id');
    }
}
