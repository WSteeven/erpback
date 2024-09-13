<?php

namespace App\Models;

use App\Models\Tareas\Etapa;
use App\Models\Tareas\CentroCosto;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

/**
 * App\Models\Tarea
 *
 * @method static where(string $string, mixed $tarea)
 * @property int $id
 * @property string $codigo_tarea
 * @property string|null $codigo_tarea_cliente
 * @property string|null $fecha_solicitud
 * @property string $titulo
 * @property string|null $observacion
 * @property string $para_cliente_proyecto
 * @property string $ubicacion_trabajo
 * @property string $medio_notificacion
 * @property bool $finalizado
 * @property string|null $imagen_informe
 * @property string|null $novedad
 * @property int|null $cliente_id
 * @property int|null $cliente_final_id
 * @property int|null $ruta_tarea_id
 * @property int|null $fiscalizador_id
 * @property int $coordinador_id
 * @property int|null $proyecto_id
 * @property int|null $etapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $metraje_tendido
 * @property int|null $centro_costo_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read CentroCosto|null $centroCosto
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\ClienteFinal|null $clienteFinal
 * @property-read \App\Models\Empleado|null $coordinador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Devolucion> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read Etapa|null $etapa
 * @property-read \App\Models\Empleado|null $fiscalizador
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pedido> $pedidos
 * @property-read int|null $pedidos_count
 * @property-read \App\Models\Proyecto|null $proyecto
 * @property-read \App\Models\RutaTarea|null $rutaTarea
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subtarea> $subtareas
 * @property-read int|null $subtareas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Traspaso> $traspasos
 * @property-read int|null $traspasos_count
 * @property-read \App\Models\UbicacionTarea|null $ubicacionTarea
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea disponibleUnaHoraFinalizar()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea estaActiva()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea fechaInicioFin()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea orderByAgendadoDesc()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea porCoordinador()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea porRol()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereCentroCostoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereClienteFinalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereCodigoTarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereCodigoTareaCliente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereCoordinadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereFechaSolicitud($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereFiscalizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereImagenInforme($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereMedioNotificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereMetrajeTendido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereNovedad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereParaClienteProyecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereRutaTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereUbicacionTrabajo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tarea whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tarea extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel, UppercaseValuesTrait, Searchable;

    const PARA_PROYECTO = 'PARA_PROYECTO';
    const PARA_CLIENTE_FINAL = 'PARA_CLIENTE_FINAL';

    const CORREO = 'CORREO';
    const LLAMADA = 'LLAMADA';
    const CHAT = 'CHAT';

    // ubicacionTrabajo
    const CLIENTE_FINAL = 'CLIENTE_FINAL';
    const RUTA = 'RUTA';

    protected $table = 'tareas';
    protected $fillable = [
        'codigo_tarea',
        'codigo_tarea_cliente',
        'fecha_solicitud',
        'titulo',
        'para_cliente_proyecto',
        'ubicacion_trabajo',
        'medio_notificacion',
        'observacion',
        'novedad',
        'imagen_informe',
        'finalizado',
        'metraje_tendido',
        'proyecto_id',
        'coordinador_id',
        'fiscalizador_id',
        'cliente_id',
        'cliente_final_id',
        'ruta_tarea_id',
        'etapa_id',
        'centro_costo_id',
    ];

    protected $casts = ['finalizado' => 'boolean'];

    private static $whiteListFilter = ['*'];

    public function toSearchableArray()
    {
        return [
            'codigo_tarea' => $this->codigo_tarea,
            'codigo_tarea_cliente' => $this->codigo_tarea_cliente,
            'titulo' => $this->titulo,
            'proyecto' => $this->proyecto?->codigo_proyecto . ' ' . $this->proyecto?->nombre,
        ];
    }

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    // Relacion uno a muchos (inversa)
    public function centroCosto()
    {
        return $this->belongsTo(CentroCosto::class);
    }
    // Relacion uno a muchos (inversa)
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    // Relacion uno a muchos (inversa)
    public function fiscalizador()
    {
        return $this->belongsTo(Empleado::class, 'fiscalizador_id', 'id');
    }

    // Relacion uno a muchos (inversa)
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede tener varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede una o varias devoluciones
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }

    /**
     * Relación uno a muchos .
     * Una tarea puede uno o varios traspasos
     */
    public function traspasos()
    {
        return $this->hasMany(Traspaso::class);
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }


    // Relacion uno a uno (inversa)
    public function ubicacionTarea()
    {
        // return $this->belongsTo(UbicacionTarea::class);
        return $this->hasOne(UbicacionTarea::class);
    }

    // Relacion uno a uno (inversa)
    public function clienteFinal()
    {
        return $this->belongsTo(ClienteFinal::class);
    }

    public function rutaTarea()
    {
        return $this->belongsTo(RutaTarea::class);
    }

    public function subtareas()
    {
        return $this->hasMany(Subtarea::class);
    }

    public function esPrimeraAsignacion($subtarea_id)
    {
        $subtareaEncontrada = $this->subtareas()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->first();
        return $subtareaEncontrada?->id == $subtarea_id;
    }


    /**
     * Relación uno a muchos .
     * Una tarea puede uno o varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    public function etapa()
    {
        return $this->belongsTo(Etapa::class);
    }

    /*********
     * Scopes
     *********/
    public function scopePorRol($query)
    {
        if (User::find(Auth::id())->hasRole(User::ROL_COORDINADOR)) return $this->scopePorCoordinador($query);
        else return $query;
    }

    public function scopePorCoordinador($query)
    {
        return $query->where('coordinador_id', Auth::user()->empleado->id);
    }

    public function scopeOrderByAgendadoDesc($query)
    {
        return $query->orderBy('fecha_hora_agendado', 'desc');
    }

    public function scopeDisponibleUnaHoraFinalizar($query)
    {
        // $activeUsers = DB::table('tareas')->select('id')->where('finali', 1);

        return $query->where('updated_at', '>=', Carbon::now()->subHour(24));
    }

    public function scopeFechaInicioFin($query)
    {
        // Obtencion de parametros
        $fechaInicio = request('fecha_inicio');
        $fechaFin = request('fecha_fin');

        // Conversion de fechas
        $fechaInicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fechaFin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();

        return $query->whereBetween('created_at', [$fechaInicio, $fechaFin])->orWhere('created_at', $fechaFin);
    }

    public function scopeEstaActiva($query)
    {
        return $query->where('finalizado', false);
    }
}
