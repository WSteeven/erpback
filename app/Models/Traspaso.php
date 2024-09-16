<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Traspaso
 *
 * @property int $id
 * @property string|null $justificacion
 * @property int $devuelta
 * @property int $solicitante_id
 * @property int $desde_cliente_id
 * @property int $hasta_cliente_id
 * @property int|null $tarea_id
 * @property int $estado_id
 * @property int $sucursal_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\EstadoTransaccion|null $estado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Inventario> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Cliente|null $prestamista
 * @property-read \App\Models\Cliente|null $prestatario
 * @property-read \App\Models\Empleado|null $solicitante
 * @property-read \App\Models\Sucursal|null $sucursal
 * @property-read \App\Models\Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso query()
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereDesdeClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereDevuelta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereEstadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereHastaClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereJustificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereSolicitanteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereSucursalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereTareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Traspaso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Traspaso extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'traspasos';
    public $fillable = [
        'justificacion',
        'devuelta',
        'solicitante_id',
        'desde_cliente_id',
        'hasta_cliente_id',
        'tarea_id',
        'estado_id',
        'sucursal_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación muchos a muchos(inversa).
     * Un traspaso tiene varios items del inventario
     */
    public function items()
    {
        return $this->belongsToMany(Inventario::class, 'detalle_inventario_traspaso', 'traspaso_id', 'inventario_id')
            ->withPivot(['cantidad'])->withTimestamps();
    }
    /**
     * Relación uno a muchos(inversa).
     * Un traspaso pertenece a una o ninguna tarea
     */
    public function tarea()
    {
        return $this->belongsTo(Tarea::class);
    }
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos pertenecen a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios traspasos pertenece a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos se hacen desde un cliente
     */
    public function prestamista()
    {
        return $this->belongsTo(Cliente::class, 'desde_cliente_id', 'id');
    }

    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos se hacen hasta un cliente
     */
    public function prestatario()
    {
        return $this->belongsTo(Cliente::class, 'hasta_cliente_id', 'id');
    }
    /**
     * Relacion uno a uno(inversa)
     * Uno o varios traspasos tienen un estado
     */
    public function estado()
    {
        return $this->belongsTo(EstadoTransaccion::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    /**
     * Obtener el listados de productos de un traspaso
     */
    public static function listadoProductos(int $id)
    {
        $items = Traspaso::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            // Log::channel('testing')->info('Log', ['Foreach de traspaso:', $item]);
            $detalleTraspaso = DetalleInventarioTraspaso::with('devoluciones')->where('traspaso_id', $item->pivot->traspaso_id)->where('inventario_id', $item->pivot->inventario_id)->first();
            $detalle = DetalleInventarioTraspaso::withSum('devoluciones', 'cantidad')->where('traspaso_id', $item->pivot->traspaso_id)->where('inventario_id', $item->pivot->inventario_id)->first();
            $row['id'] = $item->id;
            $row['producto'] = $item->detalle->producto->nombre;
            $row['detalle_id'] = $item->detalle->descripcion;
            $row['cliente_id'] = $item->cliente->empresa->razon_social;
            $row['condicion'] = $item->condicion->nombre;
            $row['cantidades'] = $item->pivot->cantidad;
            $row['devolucion'] = null;
            $row['devuelto'] = $detalle->devoluciones_sum_cantidad;
            $row['devoluciones'] = $detalleTraspaso->devoluciones;
            $results[$id] = $row;
            $id++;
        }
        // Log::channel('testing')->info('Log', ['Foreach de movimientos de devoluciones del  traspaso:', $devoluciones]);
        return $results;
    }

    public static function devolucionesRealizadas(int $id)
    {
        $items = Traspaso::find($id)->items()->get();
        $results = [];
        $id = 0;
        $row = [];
        foreach ($items as $item) {
            $detalleTraspaso = DetalleInventarioTraspaso::with('devoluciones')->where('traspaso_id', $item->pivot->traspaso_id)->where('inventario_id', $item->pivot->inventario_id)->first();
            Log::channel('testing')->info('Log', ['devoluciones que detalleTraspaso:', $detalleTraspaso->devoluciones]);
            $row['id'] = $item->id;
            $row['detalle_id'] = $item->detalle->descripcion;
            $row['cantidades'] = $item->pivot->cantidad;
            $row['devoluciones'] = $detalleTraspaso->devoluciones;
            $results[$id] = $row;
            $id++;
        }
        Log::channel('testing')->info('Log', ['devoluciones que se envian :', $results]);

        return $results;
    }
}
