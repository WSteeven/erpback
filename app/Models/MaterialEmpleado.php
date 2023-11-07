<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Auth;

class MaterialEmpleado extends Model
{
    use HasFactory, Filterable;

    protected $table = 'materiales_empleados';

    protected $fillable = [
        'cantidad_stock',
        'es_fibra',
        'despachado',
        'devuelto',
        'empleado_id',
        'detalle_producto_id',
    ];

    private static $whiteListFilter = ['*'];

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }


    public static function cargarMaterialEmpleado(DetalleProducto $detalle, $empleado_id, $cantidad)
    {
        try {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle->id)->where('empleado_id', $empleado_id)->first();
            if ($material) {
                $material->cantidad_stock += $cantidad;
                $material->despachado += $cantidad;
                $material->save();
            } else { // se crea el material
                $esFibra = !!Fibra::where('detalle_id', $detalle->id)->first();
                MaterialEmpleado::create([
                    'cantidad_stock' => $cantidad,
                    'despachado' => $cantidad,
                    'empleado_id' => $empleado_id,
                    'detalle_producto_id' => $detalle->id,
                    'es_fibra' => $esFibra,
                ]);
            }
        } catch (\Throwable $th) {
            throw $th->getMessage() . '. ' . $th->getLine();
        }
    }
}
