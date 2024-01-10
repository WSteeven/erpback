<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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

    public static function cargarMaterialEmpleado(int $detalle_id, int $empleado_id, int $cantidad, int $cliente_id)
    {
        try {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)->where('empleado_id', $empleado_id)->where('cliente_id', $cliente_id)->first();
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
    public static function descargarMaterialEmpleado(int $detalle_id, int $empleado_id, int $cantidad, int|null $cliente_id)
    {
        try {
            $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)//107
                ->where('empleado_id', $empleado_id)->where('cliente_id', $cliente_id)->first(); //5 
            if ($material) {
                $material->cantidad_stock -= $cantidad;
                $material->devuelto += $cantidad;
                $material->save();
            } else {
                $material = MaterialEmpleado::where('detalle_producto_id', $detalle_id)
                    ->where('empleado_id', $empleado_id)->where('cliente_id', null)->first();
                if ($material) {
                    $material->cantidad_stock -= $cantidad;
                    $material->devuelto += $cantidad;
                    $material->save();
                } else
                    throw new Exception('No se encontró material ' . DetalleProducto::find($detalle_id)->descripcion . ' asignado al empleado');
            }
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage() . '. ' . $th->getLine());
        }
    }
}
