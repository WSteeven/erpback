<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetallePagoComision extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'ventas_detalle_pago_comision';
    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'corte_id',
        'vendedor_id',
        'chargeback',
        'ventas',
        'valor',
        'pagado'
    ];
    private static $whiteListFilter = [
        '*',
    ];
    protected $casts = ['pagado' => 'boolean'];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function corte()
    {
        return $this->belongsTo(CortePagoComision::class);
    }


    public static function crearComisionesEmpleados(CortePagoComision $corte, $fecha_inicio, $fecha_fin)
    {
        $ventas = Venta::where(function ($query) use ($fecha_inicio, $fecha_fin) {
            $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))])
                ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
        })->where('estado_activacion', Venta::ACTIVADO)->get();
        Log::channel('testing')->info('Log', ['ventas', $ventas]);
        // Log::channel('testing')->info('Log', ['total_ventas',  $ventas->count()]);
        // Log::channel('testing')->info('Log', ['valor',  $ventas->sum('comision_vendedor')]);
        foreach ($ventas->pluck('vendedor_id') as $v) {
            $vendedor = Vendedor::find($v);
            $ventas_vendedor  = $ventas->filter(function ($venta) use ($v) {
                return $venta->vendedor_id == $v;
            });
            Log::channel('testing')->info('Log', ['vendedor',  $vendedor->empleado->nombres . ' ' . $vendedor->empleado->apellidos]);
            Log::channel('testing')->info('Log', ['ventas_vendedor',  $ventas_vendedor]);
            Log::channel('testing')->info('Log', ['total_ventas',  $ventas_vendedor->count()]);
            Log::channel('testing')->info('Log', ['chargeback',  $ventas_vendedor->sum('chargeback')]);
            Log::channel('testing')->info('Log', ['valor',  $ventas_vendedor->sum('comision_vendedor')]);
            DetallePagoComision::create();
        }
    }
}
