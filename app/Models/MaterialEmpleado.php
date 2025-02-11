<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\MaterialEmpleado
 *
 * @property int $id
 * @property int $cantidad_stock
 * @property int $es_fibra
 * @property int $empleado_id
 * @property int $detalle_producto_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $despachado
 * @property int $devuelto
 * @property int|null $cliente_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\DetalleProducto|null $detalle
 * @property-read \App\Models\Empleado|null $empleado
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado materiales()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado query()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado responsable()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado tieneStock()
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereCantidadStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereDespachado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereDetalleProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereDevuelto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereEsFibra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaterialEmpleado whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MaterialEmpleado extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'materiales_empleados';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'despachado',
        'devuelto',
        'empleado_id',
        'detalle_producto_id',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class,  'detalle_producto_id', 'id');
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    public function scopeMateriales($query)
    {
        return $query->leftJoin('detalles_productos', 'detalle_producto_id', 'detalles_productos.id')->join('productos', 'detalles_productos.producto_id', 'productos.id')->where(function ($query) {
            return $query->where('productos.categoria_id', Producto::MATERIAL)->orWhere('productos.categoria_id', Producto::EQUIPO);
        });
    }

    public function scopeTieneStock($query)
    {
        return $query->where('cantidad_stock', '>', 0);
    }

    public function scopeFilterByCategoria($query, $categoriaId = null)
    {
        // Verificar si se ha pasado un 'categoria_id'
        if ($categoriaId) {
            $query->whereHas('detalle.producto', function ($query) use ($categoriaId) {
                $query->where('categoria_id', $categoriaId);
            });
        }

        return $query;
    }

    /**
     * La función "cargarMaterialEmpleado" se utiliza para cargar materiales para un empleado, mediante un despacho de bodega o preingreso,
     * actualizando las cantidades de stock y despacho si el material ya existe, o creando una nueva
     * entrada de material si no existe.
     *
     * @param int $detalle_id El ID del detalle_producto al que pertenece el material.
     * @param int $empleado_id El parámetro empleado_id representa el ID del empleado. Se utiliza para
     * identificar al empleado específico para quien se carga el material.
     * @param int $cantidad El parámetro cantidad representa la cantidad de material a cargar para el
     * empleado.
     * @param int $cliente_id El parámetro `cliente_id` representa el ID del cliente para quien se está
     * cargando el material.
     */
    public static function cargarMaterialEmpleado(int $detalle_id, int $empleado_id, int $cantidad, int|null $cliente_id)
    {
        try {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)
                ->where('empleado_id', $empleado_id)
                ->where('cliente_id', $cliente_id)->first();
            if ($material) {
                $material->cantidad_stock += $cantidad;
                $material->despachado += $cantidad;
                $material->save();
            } else { // se crea el material
                MaterialEmpleado::create([
                    'cantidad_stock' => $cantidad,
                    'despachado' => $cantidad,
                    'empleado_id' => $empleado_id,
                    'detalle_producto_id' => $detalle_id,
                    'cliente_id' => $cliente_id,
                ]);
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        }
    }
    /**
     * La función "descargarMaterialEmpleado" se utiliza para actualizar el stock y cantidad de
     * devolución de un material asignado a un empleado.
     *
     * @param int detalle_id El ID del detalle_producto al que está asociado el material.
     * @param int empleado_id El ID del empleado al que se le asigna el material.
     * @param int cantidad La cantidad de material que es necesario descargar o descontar del stock.
     * @param int|null cliente_id El ID del cliente para quien se descarga el material.
     */
    public static function descargarMaterialEmpleado(int $detalle_id, int $empleado_id, int $cantidad, int|null $cliente_id, int|null $transaccion_cliente_id)
    {
        // try {
        $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)
            ->where('empleado_id', $empleado_id)
            ->where('cliente_id', $cliente_id)
            ->where('cantidad_stock', '>=', $cantidad)->first();
        if ($material) {
            $material->cantidad_stock -= $cantidad;
            $material->devuelto += $cantidad;
            $material->save();
        } else {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)
                ->where('empleado_id', $empleado_id)
                ->where('cliente_id', $transaccion_cliente_id)
                ->where('cantidad_stock', '>=', $cantidad)->first();
            if ($material) {
                $material->cantidad_stock -= $cantidad;
                $material->devuelto += $cantidad;
                $material->save();
            } else
                throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado');
        }
        /*  } catch (\Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        } */
    }

    /**
     * La función "cargarMaterialEmpleadoPorAnulacionDevolucion" actualiza el stock y cantidad de
     * devolución de un material asignado a un empleado en función de la anulación de una devolución de
     * producto.
     *
     * @param int detalle_id El parámetro detalle_id es un número entero que representa el ID del
     * detalle_producto que está asociado con el material que se carga para el empleado.
     * @param int empleado_id El parámetro empleado_id representa el ID del empleado.
     * @param int cantidad El parámetro cantidad representa la cantidad de material que se necesita
     * cargar o agregar al stock.
     * @param int|null cliente_id El parámetro `cliente_id` representa el ID del cliente para quien se está
     * cargando el material.
     */
    public static function cargarMaterialEmpleadoPorAnulacionDevolucion(int $detalle_id, int $empleado_id, int $cantidad, int|null $cliente_id)
    {
        try {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)->where('empleado_id', $empleado_id)->where('cliente_id', $cliente_id)->first();
            if ($material) {
                $material->cantidad_stock += $cantidad;
                $material->devuelto -= $cantidad;
                $material->save();
            } else throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado para descontar lo devuelto');
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        }
    }
    public static function actualizarMaterialesEmpleado($registroAntiguo, $registro, $empleado)
    {
        try {
            DB::beginTransaction();
            //descontamos al registro antiguo
            self::descargarMaterialEmpleado($registro['detalle_producto_id'], $empleado, $registro['stock_actual'], $registroAntiguo['cliente_id'], $registroAntiguo['cliente_id']);
            //asignamos al nuevo registro
            self::cargarMaterialEmpleado($registro['detalle_producto_id'], $empleado, $registro['stock_actual'], $registro['cliente']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
