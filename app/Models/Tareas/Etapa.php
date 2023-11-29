<?php

namespace App\Models\Tareas;

use App\Models\Empleado;
use App\Models\Proyecto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Etapa extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'vehiculos';
    protected $fillable = [
        'nombre',
        'activo',
        'responsable_id',
        'proyecto_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

     /**
     * Relación uno a muchos (inversa).
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class);
    }
    /**
     * Relación uno a muchos (inversa).
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }
}
