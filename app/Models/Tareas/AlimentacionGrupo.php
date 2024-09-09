<?php

namespace App\Models\Tareas;

use Src\App\WhereRelationLikeCondition\Tareas\GrupoWRLC;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;
use Laravel\Scout\Searchable;
use App\Models\Tarea;
use App\Models\Grupo;

class AlimentacionGrupo extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, UppercaseValuesTrait, Searchable;

    protected $table = 'tar_alimentacion_grupos';
    protected $fillable = [
        'observacion',
        'cantidad_personas',
        'precio',
        'fecha',
        'tarea_id',
        'grupo_id',
        'tipo_alimentacion_id',
    ];

    /*******************
     * Eloquent Filter
     *******************/
    private static $whiteListFilter = [
        '*',
    ];

    private $aliasListFilter = [
        'grupo.nombre' => 'grupo',
    ];

    public function EloquentFilterCustomDetection(): array
    {
        return [
            GrupoWRLC::class,
        ];
    }

    /*************************
     * Laravel Scout Search
     *************************/
    public function toSearchableArray()
    {
        $coordinador = $this->tarea?->coordinador;

        return [
            'grupo' => $this->grupo->nombre,
            'tarea' => $this->tarea->codigo_tarea,
            'coordinador' => $coordinador ? $coordinador->nombres . ' ' . $coordinador->apellidos : null,
            'tipo_alimentacion' => $this->tipoAlimentacion->descripcion,
        ];
    }

    /**************
     * Constantes
     **************/
    const PRECIO_ALIMENTACION = 3;

    /**************
     * Relaciones
     **************/
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    public function grupo()
    {
        // return $this->belongsTo(Grupo::class);
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function tipoAlimentacion()
    {
        return $this->belongsTo(SubDetalleViatico::class);
    }
}
