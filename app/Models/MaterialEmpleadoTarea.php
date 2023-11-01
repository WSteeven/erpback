<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class MaterialEmpleadoTarea extends Model
{
    use HasFactory, Filterable;

    protected $table = 'materiales_empleados_tareas';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'despachado',
        'devuelto',
        'tarea_id',
        'empleado_id',
        'detalle_producto_id',
    ];

    private static $whiteListFilter = ['*'];

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'tarea_id');
    }

    public static function cargarMaterialEmpleadoTarea(DetalleProducto $detalle, $empleado_id, $tarea_id, $cantidad)
    {
        try {
            $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle->id)
                ->where('tarea_id', $tarea_id)
                ->where('empleado_id', $empleado_id)->first();

            if ($material) {
                $material->cantidad_stock += $cantidad;
                $material->despachado += $cantidad;
                $material->save();
            } else {
                $esFibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                MaterialEmpleadoTarea::create([
                    'cantidad_stock' => $cantidad,
                    'despachado' => $cantidad,
                    'tarea_id' => $tarea_id,
                    'empleado_id' => $empleado_id,
                    'detalle_producto_id' => $detalle->id,
                    'es_fibra' => $esFibra, // Pendiente de obtener
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
