<?php

namespace App\Models\Conecel\GestionTareas;

use App\ModelFilters\TareaFilter;
use App\Models\Grupo;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Tarea extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use TareaFilter;
    use AuditableModel;

    protected $table = 'claro_tar_tareas';
    protected $fillable = [
        'aid', //actividad_id
        'source',
        'time_slot', // tiempo de consulta ¿?
        'eta',
        'end_time',
        'aworktype',
        'appt_number',
        'cname',
        'activity_workskills',
        'aworkzone',
        'direccion',
        'cemail',
        'astatus',
        'atime_of_booking',
        'atime_of_assignment',
        'lat',
        'lng',
        'duration',
        'travel_time',
        'sla',
        'raw_data',
        'received_at',
    ];
    protected $casts = [
        'raw_data' => 'array',
        'received_at' => 'datetime',
    ];
    private static array $whiteListFilter = [
        '*',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'nombre_alternativo', 'source');
    }

    /**
     * Busca en la lista de grupos el primer grupo que contenga el nombre alternativo dado.
     * Si no se encuentra ningún grupo, devuelve null.
     * @param string $nombreAlternativo
     * @return Grupo|Builder|Model|object|null
     */
    public static function obtenerGrupoRelacionado(string $nombreAlternativo)
    {
        return Grupo::where('nombre_alternativo', 'like', "%$nombreAlternativo%")->first();
    }

}
