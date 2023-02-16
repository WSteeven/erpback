<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Cargo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'cargos';
    protected $fillable = [
        'nombre'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function toSearchableArray()
    {
        return [
            'nombres' => $this->nombres,
        ];
    }


    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a uno (inversa).
     * Un cargo pertenece a un empleado.
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
