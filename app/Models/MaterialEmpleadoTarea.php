<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
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

    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'tarea_id');
    }

    public static function cargarMaterialEmpleadoTarea(DetalleProducto $detalle, $empleado_id, $tarea_id, $cantidad, int $cliente_id)
    {
        try {
            $material = MaterialEmpleadoTarea::where('detalle_producto_id', $detalle->id)
                ->where('tarea_id', $tarea_id)
                ->where('empleado_id', $empleado_id)->first();

            if ($material) {
                $material->cantidad_stock += $cantidad;
                $material->despachado += $cantidad;
                $material->cliente_id = $cliente_id;
                $material->save();
            } else {
                MaterialEmpleadoTarea::create([
                    'cantidad_stock' => $cantidad,
                    'despachado' => $cantidad,
                    'tarea_id' => $tarea_id,
                    'empleado_id' => $empleado_id,
                    'detalle_producto_id' => $detalle->id,
                    'cliente_id' => $cliente_id,
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
