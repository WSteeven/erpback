<?php

namespace App\Models;

use App\Models\Tareas\Etapa;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\StorageAttributes;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Pusher\Pusher;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\Autorizaciones;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

/**
 * App\Models\PreingresoMaterial
 *
 * @method static create(mixed $datos)
 * @property int $id
 * @property string|null $observacion
 * @property string $cuadrilla
 * @property string $num_guia
 * @property string $courier
 * @property string $fecha
 * @property int|null $proyecto_id
 * @property int|null $etapa_id
 * @property int|null $tarea_id
 * @property int|null $cliente_id
 * @property int|null $autorizador_id
 * @property int|null $solicitante_id
 * @property int|null $responsable_id
 * @property int|null $coordinador_id
 * @property int|null $autorizacion_id
 * @property string|null $observacion_aut
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, \App\Models\Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Autorizacion|null $autorizacion
 * @property-read \App\Models\Empleado|null $autorizador
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\Empleado|null $coordinador
 * @property-read Collection<int, \App\Models\DetalleProducto> $detalles
 * @property-read int|null $detalles_count
 * @property-read Etapa|null $etapa
 * @property-read \App\Models\Notificacion|null $latestNotificacion
 * @property-read Collection<int, \App\Models\Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Proyecto|null $proyecto
 * @property-read \App\Models\Empleado|null $responsable
 * @property-read \App\Models\Empleado|null $solicitante
 * @property-read \App\Models\Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereAutorizacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereAutorizadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereCoordinadorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereCourier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereCuadrilla($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereFecha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereNumGuia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereObservacionAut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PreingresoMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PreingresoMaterial extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;
    protected $table = 'preingresos_materiales';
    protected $fillable =  [
        'observacion',
        'cuadrilla',
        'num_guia',
        'courier',
        'fecha',
        'tarea_id',
        'cliente_id',
        'autorizador_id',
        'responsable_id',
        'coordinador_id',
        'autorizacion_id',
        'observacion_aut',
        'solicitante_id',
        'proyecto_id',
        'etapa_id',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
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
     * Relación uno a uno (inversa).
     * Un preingreso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos pertenecen a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos son autorizados por una persona
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un responsable
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos tienen un coordinador
     */
    public function coordinador()
    {
        return $this->belongsTo(Empleado::class, 'coordinador_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos se cargan a un proyecto.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'id');
    }

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios preingresos se cargan a una etapa de un proyecto.
     */
    public function etapa()
    {
        return $this->belongsTo(Etapa::class, 'etapa_id', 'id');
    }

    /**
     * Relación uno a uno(inversa).
     * Uno o varios preingresos solo pueden tener una autorización.
     */
    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class);
    }

    public function detalles()
    {
        return $this->belongsToMany(DetalleProducto::class, 'item_detalle_preingreso_material', 'preingreso_id', 'detalle_id')
            ->withPivot('id', 'descripcion', 'cantidad', 'serial', 'punta_inicial', 'punta_final', 'unidad_medida_id', 'condicion_id', 'fotografia')->withTimestamps();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    public static function listadoProductos($id)
    {
        $items =  PreingresoMaterial::find($id)->detalles()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            $row['id'] = $item->pivot->id;
            $row['producto'] = $item->producto->nombre;
            $row['detalle_id'] = $item->id;
            $row['descripcion'] = $item->pivot->descripcion;
            $row['categoria'] = $item->producto->categoria->nombre;
            $row['unidad_medida'] = $item->producto->unidadMedida->nombre;
            $row['condicion'] = Condicion::find($item->pivot->condicion_id)?->nombre;
            $row['serial'] = $item->pivot->serial;
            $row['cantidad'] = $item->pivot->cantidad;
            $row['punta_inicial'] = $item->pivot->punta_inicial;
            $row['punta_final'] = $item->pivot->punta_final;
            $row['fotografia'] = $item->pivot->fotografia ? url($item->pivot->fotografia) : null;
            $results[$id] = $row;
            $id++;
        }

        return $results;
    }
}
