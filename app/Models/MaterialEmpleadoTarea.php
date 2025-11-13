<?php

namespace App\Models;

use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\MaterialEmpleadoTarea
 *
 * @property int $id
 * @property int $cantidad_stock
 * @property int $es_fibra
 * @property int $tarea_id
 * @property int $empleado_id
 * @property int $detalle_producto_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $despachado
 * @property int $devuelto
 * @property int|null $cliente_id
 * @property int|null $proyecto_id
 * @property int|null $etapa_id
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleProducto|null $detalle
 * @property-read Tarea|null $tarea
 * @method static Builder|MaterialEmpleadoTarea acceptRequest(?array $request = null)
 * @method static Builder|MaterialEmpleadoTarea deEtapa($proyecto_id, $etapa_id)
 * @method static Builder|MaterialEmpleadoTarea deProyecto($proyecto_id)
 * @method static Builder|MaterialEmpleadoTarea deTarea($tarea_id)
 * @method static Builder|MaterialEmpleadoTarea devolverFiltroTareaEtapaProyecto()
 * @method static Builder|MaterialEmpleadoTarea filter(?array $request = null)
 * @method static Builder|MaterialEmpleadoTarea ignoreRequest(?array $request = null)
 * @method static Builder|MaterialEmpleadoTarea materiales()
 * @method static Builder|MaterialEmpleadoTarea newModelQuery()
 * @method static Builder|MaterialEmpleadoTarea newQuery()
 * @method static Builder|MaterialEmpleadoTarea query()
 * @method static Builder|MaterialEmpleadoTarea responsable()
 * @method static Builder|MaterialEmpleadoTarea setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|MaterialEmpleadoTarea setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|MaterialEmpleadoTarea setLoadInjectedDetection($load_default_detection)
 * @method static Builder|MaterialEmpleadoTarea soloEtapas()
 * @method static Builder|MaterialEmpleadoTarea soloProyectos()
 * @method static Builder|MaterialEmpleadoTarea soloTareas()
 * @method static Builder|MaterialEmpleadoTarea tieneStock()
 * @method static Builder|MaterialEmpleadoTarea whereCantidadStock($value)
 * @method static Builder|MaterialEmpleadoTarea whereClienteId($value)
 * @method static Builder|MaterialEmpleadoTarea whereCreatedAt($value)
 * @method static Builder|MaterialEmpleadoTarea whereDespachado($value)
 * @method static Builder|MaterialEmpleadoTarea whereDetalleProductoId($value)
 * @method static Builder|MaterialEmpleadoTarea whereDevuelto($value)
 * @method static Builder|MaterialEmpleadoTarea whereEmpleadoId($value)
 * @method static Builder|MaterialEmpleadoTarea whereEsFibra($value)
 * @method static Builder|MaterialEmpleadoTarea whereEtapaId($value)
 * @method static Builder|MaterialEmpleadoTarea whereId($value)
 * @method static Builder|MaterialEmpleadoTarea whereProyectoId($value)
 * @method static Builder|MaterialEmpleadoTarea whereTareaId($value)
 * @method static Builder|MaterialEmpleadoTarea whereUpdatedAt($value)
 * @mixin Eloquent
 */
class MaterialEmpleadoTarea extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'materiales_empleados_tareas';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'despachado',
        'devuelto',
        'tarea_id',
        'empleado_id',
        'cliente_id',
        'detalle_producto_id',
        'proyecto_id',
        'etapa_id',
    ];

    private static $whiteListFilter = ['*'];

    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class,  'detalle_producto_id', 'id');
    }
    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    public function scopeMateriales($query)
    {
        return $query->join('detalles_productos', 'detalle_producto_id', 'detalles_productos.id')->join('productos', 'detalles_productos.producto_id', 'productos.id')->where(function ($query) {
            return $query->where('productos.categoria_id', Producto::MATERIAL)->orWhere('productos.categoria_id', Producto::EQUIPO);
        });
    }

    public function scopeTieneStock($query)
    {
        return $query->where('cantidad_stock', '>', 0);
    }

    /**
     *
     */
    public function scopeDeProyecto($query, $proyecto_id)
    {
        return $query->where('proyecto_id', $proyecto_id)->where('etapa_id', null);
    }

    public function scopeDeEtapa($query, $proyecto_id, $etapa_id)
    {
        return $query->where('proyecto_id', $proyecto_id)->where('etapa_id', $etapa_id);
    }

    public function scopeDeTarea($query, $tarea_id)
    {
        return $query->where('proyecto_id', null)->where('etapa_id', null)->where('tarea_id', $tarea_id);
    }

    /**
     *
     */
    public function scopeSoloProyectos($query)
    {
        return $query->where('proyecto_id', '!=', null)->where('etapa_id', null); //->where('tarea_id', null);
    }

    public function scopeSoloEtapas($query)
    {
        return $query->where('proyecto_id', '!=', null)->where('etapa_id', '!=', null); // ->where('tarea_id', null);
    }

    public function scopeSoloTareas($query)
    {
        return $query->where('proyecto_id', null)->where('etapa_id', null)->where('tarea_id', '!=', null);
    }

    /**
     * Scope dinamico
     */
    public function scopeDevolverFiltroTareaEtapaProyecto($query)
    {
        if (request('filtrar_por_tarea')) return $query->deTarea(request('tarea_id'));
        if (request('filtrar_por_etapa')) return $query->deEtapa(request('proyecto_id'), request('etapa_id'));
        if (request('filtrar_por_proyecto')) return $query->deProyecto(request('proyecto_id'));
    }

    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'tarea_id');
    }

    // Suma los productos del stock

    /**
     * @throws Throwable
     */
    public static function cargarMaterialEmpleadoTarea(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int|null $cliente_id, int|null $proyecto_id, int|null $etapa_id)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)
            ->where('tarea_id', $tarea_id)
            ->where('cliente_id', $cliente_id)
            ->where('empleado_id', $empleado_id)
            ->when('proyecto_id', function ($query) use ($proyecto_id) {
                $query->where('proyecto_id', $proyecto_id);
            })
            ->when('etapa_id', function ($query) use ($etapa_id) {
                $query->where('etapa_id', $etapa_id);
            })
            ->first();

        if ($material) {
            $material->cantidad_stock += $cantidad;
            $material->despachado += $cantidad;
            $material->save();
        } else {
            MaterialEmpleadoTarea::create([
                'cantidad_stock' => $cantidad,
                'despachado' => $cantidad,
                'tarea_id' => $tarea_id,
                'empleado_id' => $empleado_id,
                'detalle_producto_id' => $detalle_id,
                'cliente_id' => $cliente_id,
                'proyecto_id' => $proyecto_id,
                'etapa_id' => $etapa_id,
            ]);
        }
    }

    // Descuenta los productos del stock

    /**
     * @throws Throwable
     */
    public static function descargarMaterialEmpleadoTarea(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int $cliente_id)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)
            ->where('tarea_id', $tarea_id)
            ->where('cliente_id', $cliente_id)
            ->where('empleado_id', $empleado_id)->first();

        if ($material) {
            $material->cantidad_stock -= $cantidad;
            $material->devuelto += $cantidad;
            $material->save();
        } else {
            $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)
                ->where('tarea_id', $tarea_id)
                ->whereNull('cliente_id')
                ->where('empleado_id', $empleado_id)->first();
            if ($material) {
                $material->cantidad_stock -= $cantidad;
                $material->devuelto += $cantidad;
                $material->save();
            } else
                throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado en la tarea seleccionada');
        }
    }

    /**
     * La función `cargarMaterialEmpleadoTareaPorAnulacionDevolucion` se utiliza para actualizar el
     * stock y cantidad de devolución de un material asignado a un empleado para una tarea específica,
     * en función de la cancelación o anulación de una devolución de material.
     *
     * @param int $detalle_id
     * @param int $empleado_id
     * @param int $tarea_id
     * @param int $cantidad
     * @param int|null $cliente_id
     * @param int|null $proyecto_id
     * @param int|null $etapa_id
     * @throws Throwable
     */
    public static function cargarMaterialEmpleadoTareaPorAnulacionDevolucion(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int|null $cliente_id, int|null $proyecto_id, int|null $etapa_id)
    {
        $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)
            ->where('tarea_id', $tarea_id)
            ->where('cliente_id', $cliente_id)
            ->where('empleado_id', $empleado_id)
            ->when('proyecto_id', function ($query) use ($proyecto_id) {
                $query->where('proyecto_id', $proyecto_id);
            })
            ->when('etapa_id', function ($query) use ($etapa_id) {
                $query->where('etapa_id', $etapa_id);
            })
            ->first();

        if ($material) {
            $material->cantidad_stock += $cantidad;
            $material->devuelto -= $cantidad;
            $material->save();
        } else throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado para descontar lo devuelto');
    }

    /**
     * @throws Throwable
     */
    public static function actualizarMaterialesEmpleadoTarea($registroAntiguo, $registro, $empleado)
    {
        try {
            DB::beginTransaction();
            //descontamos al registro antiguo
            self::descargarMaterialEmpleadoTarea($registro['detalle_producto_id'], $empleado, $registroAntiguo['tarea_id'], $registro['stock_actual'], $registroAntiguo['cliente_id']);
            //asignamos al nuevo registro
            self::cargarMaterialEmpleadoTarea($registro['detalle_producto_id'], $empleado, $registroAntiguo['tarea_id'], $registro['stock_actual'], $registro['cliente'], null, null);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
