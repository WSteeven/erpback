<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class RetencionChargeback extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;
    protected $table = 'ventas_retenciones_chargebacks';
    protected $fillable = [
        'venta_id',
        'vendedor_id',
        'fecha_retencion',
        'valor_retenido',
        'pagado',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class); //->with('empleado');
    }
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id'); //->with('empleado');
    }

    public static function crearRetencionesChargebackCorte(Vendedor $vendedor, $fecha_inicio, $fecha_fin)
    {
        Log::channel('testing')->info('Log', ['args', $vendedor, $fecha_inicio, $fecha_fin]);
        try {
            DB::beginTransaction();
            if ($vendedor->modalidad->umbral_minimo > 0) {
                Log::channel('testing')->info('Log', ['if', $vendedor->modalidad->umbral_minimo]);
                $ventas = Venta::where(function ($query) use ($fecha_inicio, $fecha_fin) {
                    $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
                    // ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
                })->where('estado_activacion', Venta::ACTIVADO)
                    ->where('vendedor_id', $vendedor->empleado_id)
                    ->skip($vendedor->modalidad->umbral_minimo)->take(100)->get(); // escapar las ventas del umbral minimo
                Log::channel('testing')->info('Log', ['if', $ventas]);
            } else {
                Log::channel('testing')->info('Log', ['else', $vendedor->modalidad->umbral_minimo]);
                $ventas = Venta::where(function ($query) use ($fecha_inicio, $fecha_fin) {
                    $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
                    // ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
                })->where('estado_activacion', Venta::ACTIVADO)
                    ->where('vendedor_id', $vendedor->empleado_id)->get(); // toma todas las ventas cuando es freelance
                Log::channel('testing')->info('Log', ['else', $ventas]);
            }
            // Log::channel('testing')->info('Log', ['ventas', $ventas]);
            if ($ventas) {
                foreach ($ventas as $venta) {
                    Log::channel('testing')->info('Log', ['venta en foreach', $venta]);
                    RetencionChargeback::create([
                        'venta_id' => $venta->id,
                        'vendedor_id' => $vendedor->empleado_id,
                        'fecha_retencion' => Carbon::now(),
                        'valor_retenido' => $venta->comision_vendedor * .1
                    ]);
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en crearRetencionesChargeback', $th->getLine(), $th->getMessage()]);
            DB::rollback();
            throw $th;
        }
    }

    public static function eliminarRetencionesPorAnulacionCorte($fecha)
    {
        try {
            $retenciones = RetencionChargeback::where('fecha_retencion', $fecha)->get();
            foreach ($retenciones as $retencion) {
                $retencion->delete();
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en eliminarRetencionesPorAnulacionCorte', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
