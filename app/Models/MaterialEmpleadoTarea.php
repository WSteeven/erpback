<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
        $request = request()->all();
        Log::channel('testing')->info('Log', compact('request'));
        if (request('filtrar_por_tarea')) return $query->deTarea(request('tarea_id'));
        if (request('filtrar_por_etapa')) return $query->deEtapa(request('proyecto_id'), request('etapa_id'));
        if (request('filtrar_por_proyecto')) return $query->deProyecto(request('proyecto_id'));
    }

    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'tarea_id');
    }

    // Suma los productos del stock
    public static function cargarMaterialEmpleadoTarea(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int $cliente_id, int|null $proyecto_id, int|null $etapa_id)
    {
        try {
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Descuenta los productos del stock
    public static function descargarMaterialEmpleadoTarea(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int $cliente_id)
    {
        try {
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
                    ->where('cliente_id', null)
                    ->where('empleado_id', $empleado_id)->first();
                if ($material) {
                    $material->cantidad_stock -= $cantidad;
                    $material->devuelto += $cantidad;
                    $material->save();
                } else
                    throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado en la tarea seleccionada');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * La función `cargarMaterialEmpleadoTareaPorAnulacionDevolucion` se utiliza para actualizar el
     * stock y cantidad de devolución de un material asignado a un empleado para una tarea específica,
     * en función de la cancelación o anulación de una devolución de material.
     * 
     * @param int detalle_id El ID del producto detallado.
     * @param int empleado_id El parámetro `empleado_id` representa el ID del empleado.
     * @param int tarea_id El parámetro `tarea_id` representa el ID de la tarea para la cual se carga
     * el material para el empleado.
     * @param int cantidad El parámetro cantidad representa la cantidad de material que se necesita
     * cargar para el empleado y tarea debido a una baja o devolución.
     * @param int|null cliente_id El parámetro `cliente_id` representa el ID del cliente para quien se está
     * cargando el material.
     * @param int proyecto_id El parámetro "proyecto_id" es un número entero opcional que representa el
     * ID de un proyecto. Se utiliza para filtrar el material por proyecto si se proporciona. Si no se
     * proporciona, no se utilizará en la consulta.
     * @param int etapa_id El parámetro "etapa_id" representa el ID de una etapa o fase de un proyecto.
     * Se utiliza en la función para filtrar el material asignado a un empleado según la etapa
     * específica del proyecto.
     */
    public static function cargarMaterialEmpleadoTareaPorAnulacionDevolucion(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int|null $cliente_id, int|null $proyecto_id, int|null $etapa_id)
    {
        try {
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
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
