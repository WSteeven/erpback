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

    protected $table = 'ventas_detalles_pagos_comisiones';
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
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }

    public function corte()
    {
        return $this->belongsTo(CortePagoComision::class);
    }


    public static function crearComisionesEmpleados(CortePagoComision $corte)
    {
        try {
            $ventas = Venta::where(function ($query) use ($corte) {
                $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))])
                    ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))]);
            })->where('estado_activacion', Venta::ACTIVADO)->get();
            Log::channel('testing')->info('Log', ['ventas crearComisionesEmpleado', $ventas]);
            $ventas_vendedores_ids = $ventas->unique('vendedor_id')->pluck('vendedor_id');
            foreach ($ventas_vendedores_ids as $v) {
                $vendedor = Vendedor::find($v);
                $alcanza_umbral = Vendedor::verificarVentasMensuales($vendedor, $corte->fecha_fin);
                [$ventas_vendedor, $ventas_sin_comision, $total_comisiones]  = Vendedor::obtenerVentasConComision($vendedor, $corte->fecha_inicio, $corte->fecha_fin);
                Log::channel('testing')->info('Log', ['ventas_vendedor?', $ventas_vendedor,  $vendedor]);
                if ($ventas_vendedor->count() > 0) {
                    $detalle =  DetallePagoComision::create([
                        'fecha_inicio' => $corte->fecha_inicio,
                        'fecha_fin' => $corte->fecha_fin,
                        'corte_id' => $corte->id,
                        'vendedor_id' => $vendedor->empleado_id,
                        'chargeback' => $ventas_vendedor->sum('chargeback'),
                        'ventas' => $ventas_vendedor->count(),
                        'valor' => $alcanza_umbral ? $total_comisiones * .45 : 0,
                        'pagado' => !$alcanza_umbral
                    ]);
                    Log::channel('testing')->info('Log', ['detalle creado',  $detalle]);
                }
                if ($alcanza_umbral) {
                    RetencionChargeback::crearRetencionesChargebackCorte($vendedor, $corte->fecha_inicio, $corte->fecha_fin);
                }
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en crear comisiones empleado', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
