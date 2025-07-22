<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use App\Models\Seguridad\Zona; // Agregar import de Zona
use App\Models\Seguridad\ActividadBitacora;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

// Importar las clases WRLC que necesites crear
use Src\App\WhereRelationLikeCondition\Bitacora\ZonaWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\AgenteTurnoWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\ProtectorWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\ConductorWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\FechaInicioTurnoWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\FechaFinTurnoWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\JornadaWRLC;
use Src\App\WhereRelationLikeCondition\Bitacora\ObservacionesWRLC;


class Bitacora extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_bitacoras';
    protected $fillable = [
        'fecha_hora_inicio_turno',
        'fecha_hora_fin_turno',
        'jornada',
        'observaciones',
        'prendas_recibidas_ids',
        'zona_id',
        'agente_turno_id',
        'protector_id',
        'conductor_id',
        // Campos para la revision de la bitacora
        'revisado_por_supervisor',
        'retroalimentacion_supervisor',
    ];

    // Agregar casting para fechas
    protected $casts = [
        'fecha_hora_inicio_turno' => 'datetime',
        'fecha_hora_fin_turno' => 'datetime',
    ];

    /*******************
     * Eloquent Filter
     *******************/

    // Lista blanca de campos que se pueden filtrar
    private static $whiteListFilter = [
        '*',
        'zona.nombre',
        'agenteTurno.nombres',
        'agenteTurno.apellidos',
        'protector.nombres',
        'protector.apellidos',
        'conductor.nombres',
        'conductor.apellidos',
        'fecha_hora_inicio_turno',
        'fecha_hora_fin_turno',
        'jornada',
        'observaciones',
    ];

    // Alias para facilitar el filtrado
    private $aliasListFilter = [
        'zona.nombre' => 'zona',
        'agenteTurno.nombres' => 'agente_turno',
        'agenteTurno.apellidos' => 'agente_turno',
        'protector.nombres' => 'protector',
        'protector.apellidos' => 'protector',
        'conductor.nombres' => 'conductor',
        'conductor.apellidos' => 'conductor',
        'fecha_hora_inicio_turno' => 'fecha_inicio',
        'fecha_hora_fin_turno' => 'fecha_fin',
    ];

    // Campos que no se deben filtrar
    static $noFiltrar = ['prendas_recibidas_ids'];

    /**
     * Configuración de detección personalizada para filtros
     */
    public function EloquentFilterCustomDetection(): array
    {
        return [
            ZonaWRLC::class,
            JornadaWRLC::class,
            AgenteTurnoWRLC::class,
            ProtectorWRLC::class,
            ConductorWRLC::class,
            ObservacionesWRLC::class,
        ];
    }

    /**************
     * Relaciones
     **************/
    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function agenteTurno()
    {
        return $this->belongsTo(Empleado::class, 'agente_turno_id', 'id');
    }

    public function getEmpleadoAttribute()
    {
        return $this->agenteTurno;
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'agente_turno_id');
    }


    public function protector()
    {
        return $this->belongsTo(Empleado::class, 'protector_id', 'id');
    }

    public function conductor()
    {
        return $this->belongsTo(Empleado::class, 'conductor_id', 'id');
    }

    public function actividades()
    {
        return $this->hasMany(ActividadBitacora::class, 'bitacora_id');
    }



    /************
     * Scopes
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('jornada', 'like', "%{$term}%")
                ->orWhere('observaciones', 'like', "%{$term}%")
                ->orWhere('fecha_hora_inicio_turno', 'like', "%{$term}%")
                ->orWhere('fecha_hora_fin_turno', 'like', "%{$term}%")
                ->orWhereHas('zona', function ($subQ) use ($term) {
                    $subQ->where('nombre', 'like', "%{$term}%");
                })
                ->orWhereHas('agenteTurno', function ($subQ) use ($term) {
                    $subQ->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellidos', 'like', "%{$term}%");
                })
                ->orWhereHas('protector', function ($subQ) use ($term) {
                    $subQ->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellidos', 'like', "%{$term}%");
                })
                ->orWhereHas('conductor', function ($subQ) use ($term) {
                    $subQ->where('nombres', 'like', "%{$term}%")
                        ->orWhere('apellidos', 'like', "%{$term}%");
                });
        });
    }

    /**
     * Scope para filtrar por jornada
     */
    public function scopeJornada($query, $jornada)
    {
        return $query->where('jornada', $jornada);
    }

    /**
     * Scope para filtrar por rango de fechas
     */
    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha_hora_inicio_turno', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope para filtrar por zona
     */
    public function scopePorZona($query, $zonaId)
    {
        return $query->where('zona_id', $zonaId);
    }

    /**
     * Scope para filtrar por empleado (cualquier rol)
     */
    public function scopePorEmpleado($query, $empleadoId)
    {
        return $query->where(function ($q) use ($empleadoId) {
            $q->where('agente_turno_id', $empleadoId)
                ->orWhere('protector_id', $empleadoId)
                ->orWhere('conductor_id', $empleadoId);
        });
    }
}
