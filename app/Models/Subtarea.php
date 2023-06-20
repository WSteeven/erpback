<?php

namespace App\Models;

use App\Http\Resources\EmpleadoResource;
use App\ModelFilters\SubtareasFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Src\App\WhereRelationLikeCondition\Subtarea\CodigoTareaWRLC;
use Src\App\WhereRelationLikeCondition\Subtarea\CantidadAdjuntosWRLC;
use Src\App\WhereRelationLikeCondition\Subtarea\FechaSolicitudWRLC;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\WhereRelationLikeCondition\Subtarea\ProyectoWRLC;
use Src\App\WhereRelationLikeCondition\TrabajoCoordinadorWRLC;

class Subtarea extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait, SubtareasFilter;

    const CREADO = 'CREADO';
    const ASIGNADO = 'ASIGNADO';
    const AGENDADO = 'AGENDADO';
    const EJECUTANDO = 'EJECUTANDO';
    const PAUSADO = 'PAUSADO';
    const SUSPENDIDO = 'SUSPENDIDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';

    // Modo de asignacion de trabajo
    const POR_GRUPO = 'POR_GRUPO';
    const POR_EMPLEADO = 'POR_EMPLEADO';

    protected $table = "subtareas";
    protected $fillable = [
        'codigo_subtarea',
        'titulo',
        'descripcion_completa',
        'observacion',
        'estado',
        'modo_asignacion_trabajo',
        'fecha_hora_creacion',
        'fecha_hora_asignacion',
        'fecha_hora_agendado',
        'fecha_hora_ejecucion',
        'fecha_hora_realizado',
        'fecha_hora_finalizacion',
        'fecha_hora_cancelado',
        'motivo_cancelado_id',
        'es_dependiente',
        'es_ventana',
        'fecha_inicio_trabajo',
        'hora_inicio_trabajo',
        'hora_fin_trabajo',
        'tiempo_estimado',
        'empleados_designados',
        'tipo_trabajo_id',
        'tarea_id',
        'grupo_id',
        'empleado_id',
        'subtarea_dependiente_id',
        'coordinador_id',
        'seguimiento_id',
    ];

    protected $casts = [
        'es_dependiente' => 'boolean',
        'es_ventana' => 'boolean',
        'tiene_subtrabajos' => 'boolean',
        'empleados_designados' => 'json',
    ];

    /*******************
     * Eloquent Filter
     *******************/
    private static $whiteListFilter = [
        '*',
        /* 'cliente.empresa.razon_social',
        'tipo_trabajo.descripcion', */
        // 'canton',
        //'tarea.coordinador.nombres',
        // 'proyecto.codigo_proyecto',
        'cantidad_adjuntos',
        'tarea.fecha_solicitud',
        // 'grupo',
        //'tarea.codigo_tarea',
        //'proyecto.canton.canton'
    ];

    private $aliasListFilter = [
        /* 'cliente.empresa.razon_social' => 'cliente',
        'tipo_trabajo.descripcion' => 'tipo_trabajo', */
        //'tarea.coordinador.nombres' => 'coordinador',
        //'tarea.codigo_tarea' => 'tarea',
        //'proyecto.canton.canton' => 'canton',
        // 'proyecto.codigo_proyecto' => 'proyecto',
        'tarea.fecha_solicitud' => 'fecha_solicitud',
    ];

    public function EloquentFilterCustomDetection(): array
    {
        return [
            /* TrabajoClienteWRLC::class,
            TrabajoTipoTrabajoWRLC::class,
            TrabajoFechaHoraCreacionWRLC::class,
            TrabajoCantonWRLC::class, */
            //TrabajoCoordinadorWRLC::class,
            // ProyectoWRLC::class,
            CantidadAdjuntosWRLC::class,
            FechaSolicitudWRLC::class,
            //CodigoTareaWRLC::class,
        ];
    }

    /* public function setFechaInicioTrabajoAttribute($value)
    {
        $this->attributes['fecha_inicio_trabajo'] = (new Carbon($value))->format('Y-m-d');
    }

    public function getFechaInicioTrabajoAttribute($value)
    {
        $this->attributes['fecha_inicio_trabajo'] = Carbon::parse($value)->format('Y-m-d');
    } */
    public function attachMotivoSuspendido($id)
    {
        $this->motivoSuspendido()->attach($id);
        $this->touch();
    }

    // Relacion uno a muchos (inversa)
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }

    // Relacion uno a muchos (inversa)
    public function grupoResponsable(): BelongsTo
    {
        // Log::channel('testing')->info('Log', ['Coordinador: ', 'Dentro de la relacion ...']);
        return $this->belongsTo(Grupo::class, 'grupo_id', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function tipo_trabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }

    /**
     * Relación uno a muchos .
     * Una subtarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    // Relacion uno a muchos
    public function archivos()
    {
        return $this->hasMany(ArchivoSubtarea::class);
    }

    public function pausasSubtarea()
    {
        return $this->hasMany(PausaSubtarea::class);
    }

    public function motivoSuspendido()
    {
        return $this->belongsToMany(MotivoSuspendido::class)->withPivot('empleado_id')->withTimestamps();
    }

    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'subtarea_dependiente');
    }

    /* public function motivoSuspendido()
    {
        return $this->belongsTo(MotivoSuspendido::class);
    } */

    public function motivoCancelado()
    {
        return $this->belongsTo(MotivoSuspendido::class, 'motivo_cancelado_id', 'id');
    }

    public function tecnicosPrincipales($empleados)
    {
        // return EmpleadoResource::collection(Empleado::whereIn('id', $ids)->get());
        // return Empleado::whereIn('id', $ids)->get()->map(fn ($item) => [

        return $empleados->map(fn ($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function otrosTecnicos($empleados)
    {
        //$empleados->filter(fn($item) => $item->);
        return $empleados->map(fn ($item) => [
            'id' => $item->id,
            'identificacion' => $item->identificacion,
            'nombres' => $item->nombres,
            'apellidos' => $item->apellidos,
            'telefono' => $item->telefono,
            'fecha_nacimiento' => $item->fecha_nacimiento,
            'email' => $item->user ? $item->user->email : '',
            'jefe' => $item->jefe ? $item->jefe->nombres . ' ' . $item->jefe->apellidos : 'N/A',
            'usuario' => $item->user->name,
            'sucursal' => $item->sucursal->lugar,
            'estado' => $item->estado,
            'grupo' => $item->grupo?->nombre,
            'disponible' => $item->disponible,
            'roles' => implode(', ', $item->user->getRoleNames()->toArray()),
        ]);
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class)->withPivot('es_responsable');
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /*********
     * Scopes
     *********/
    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }
    public function scopeFechaActual($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '=', Carbon::today());
    }

    public function scopeFechaFuturo($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '>', Carbon::today());
    }

    public function scopeAnterioresNoFinalizados($query)
    {
        return $query->whereDate('fecha_inicio_trabajo', '<=', Carbon::today())->whereIn('estado', [Subtarea::AGENDADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO]);
    }

    public function scopeNoEstaRealizado($query)
    {
        return $query->where('estado', '!=', Subtarea::REALIZADO);
    }

    public function scopeNoEsStandby($query)
    {
        return $query->whereNotIn('tipo_trabajo_id', TipoTrabajo::select('id')->where('descripcion', 'STANDBY'));
    }

    public function scopeSubtareasCoordinador($query, $coordinador) //HasManyThrough
    {
        // return $this->hasManyThrough(Subtarea::class, Tarea::class, 'coordinador_id');
        // Log::channel('testing')->info('Log', ['Coordinador: ', $coordinador]);
        return DB::table('subtareas')->join('tareas', 'subtareas.tarea_id', '=', 'tareas.id')->where('tareas.coordinador_id', $coordinador);
    }
}
