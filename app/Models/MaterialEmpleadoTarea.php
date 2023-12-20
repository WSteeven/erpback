<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
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

    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'tarea_id');
    }

    public static function cargarMaterialEmpleadoTarea(int $detalle_id, int $empleado_id, int $tarea_id, int $cantidad, int $cliente_id)
    {
        try {
            $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle_id)
                ->where('tarea_id', $tarea_id)
                ->where('cliente_id', $cliente_id)
                ->where('empleado_id', $empleado_id)->first();

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
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
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
