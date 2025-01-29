<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\SeguimientoMaterialStock
 *
 * @property int $id
 * @property int $stock_actual
 * @property int $cantidad_utilizada
 * @property int $subtarea_id
 * @property int $empleado_id
 * @property int $detalle_producto_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cliente_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Cliente|null $cliente
 * @property-read \App\Models\DetalleProducto|null $detalleProducto
 * @property-read \App\Models\Empleado|null $empleado
 * @property-read \App\Models\Subtarea|null $subtarea
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock responsable()
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereCantidadUtilizada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereDetalleProductoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereEmpleadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereStockActual($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereSubtareaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeguimientoMaterialStock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeguimientoMaterialStock extends Model implements Auditable
{
    use HasFactory, AuditableModel;

    protected $table = 'seguimientos_materiales_stock';
    protected $fillable = [
        'stock_actual',
        'cantidad_utilizada',
        'empleado_id',
        'subtarea_id',
        'detalle_producto_id',
        'cliente_id',
    ];

    /*************
     * Relaciones
     *************/
    public function subtarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'subtarea_id');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function scopeResponsable($query)
    {
        return $query->where('empleado_id', Auth::user()->empleado->id);
    }

    /************************************************
     * Reporte Excel - Formulario producto Empleados
     ************************************************/
    public static function filtrarSeguimientoMaterialStockExcel($request)
    {
        $query = SeguimientoMaterialStock::where('cantidad_utilizada', '>', 0)->where('empleado_id', $request['responsable']);

        // Manejo de las fechas usando Carbon
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = $request->fecha_fin
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : now();

        $query->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        return self::mapear($query->orderByDesc('id')->get());
    }

    private static function mapear($seguimientos)
    {
        $results = [];

        foreach ($seguimientos as $seguimiento) {
            $results[] = [
                'id' => $seguimiento->id,
                'fecha_actualizacion' => $seguimiento->updated_at,
                'producto' => $seguimiento->detalleProducto->producto->nombre,
                'descripcion' => $seguimiento->detalleProducto->descripcion,
                'serial' => $seguimiento->serial,
                'categoria' => $seguimiento->detalleProducto->producto->categoria->nombre,
                'cantidad' => $seguimiento->cantidad_utilizada,
                'cliente' => $seguimiento->cliente?->empresa->razon_social,
                'cliente_id' => $seguimiento->cliente_id,
                'detalle_producto_id' => $seguimiento->detalle_producto_id,
                'subtarea' => $seguimiento->subtarea->codigo_subtarea,
            ];
        }

        return $results;
    }
}
