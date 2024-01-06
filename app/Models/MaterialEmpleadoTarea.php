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
        return $query->join('detalles_productos', 'detalle_producto_id', 'detalles_productos.id')->join('productos', 'detalles_productos.producto_id', 'productos.id')->where('productos.categoria_id', Producto::MATERIAL);
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
}
