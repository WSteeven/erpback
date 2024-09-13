<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Ventas\DetallePagoComision
 *
 * @property int $id
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int|null $corte_id
 * @property int|null $vendedor_id
 * @property string|null $chargeback
 * @property int|null $ventas
 * @property string $valor
 * @property bool $pagado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\CortePagoComision|null $corte
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision query()
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereChargeback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereCorteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereFechaFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereFechaInicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision wherePagado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereValor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DetallePagoComision whereVentas($value)
 * @mixin \Eloquent
 */
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
            $total_comisiones = 0;
            $ventas = Venta::where(function ($query) use ($corte) {
                $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))])
                    ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($corte->fecha_inicio)), date('Y-m-d', strtotime($corte->fecha_fin))]);
            })->where('estado_activacion', Venta::ACTIVADO)->where('comisiona', true)->get();
            Log::channel('testing')->info('Log', ['ventas crearComisionesEmpleado', $ventas]);
            $ventas_vendedores_ids = $ventas->unique('vendedor_id')->pluck('vendedor_id');
            foreach ($ventas_vendedores_ids as $v) {
                $vendedor = Vendedor::find($v);
                $alcanza_umbral = Vendedor::verificarVentasMensuales($vendedor, $corte->fecha_fin);
                $ventas_vendedor = $ventas->filter(function ($venta) use ($vendedor) {
                    return $venta->vendedor_id == $vendedor->empleado_id;
                });
                foreach ($ventas_vendedor as $venta) {
                    if (($venta->fecha_activacion >= $corte->fecha_inicio && $venta->fecha_activacion <= $corte->fecha_fin) && (Carbon::parse($venta->fecha_pago_primer_mes)->format('Y-m-d') >= $corte->fecha_inicio && Carbon::parse($venta->fecha_pago_primer_mes)->format('Y-m-d') <= $corte->fecha_fin)) {
                        Log::channel('testing')->info('Log', ['Venta que se repite en ambas fechas?', $venta]);
                        [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
                        $total_comisiones += $comision_valor;
                    }
                    [$comision_valor, $comision] = Comision::calcularComisionVenta($venta->vendedor_id, $venta->producto_id, $venta->forma_pago);
                    $total_comisiones += $comision_valor;
                }
                // [$ventas_vendedor, $total_comisiones]  = Vendedor::obtenerVentasConComision($vendedor, $corte->fecha_inicio, $corte->fecha_fin);
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
                    // if ($alcanza_umbral) {
                    RetencionChargeback::crearRetencionesChargebackCorte($vendedor, $ventas_vendedor, $corte->fecha_inicio, $corte->fecha_fin);
                    // }
                }
                $total_comisiones = 0;
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['error en crear comisiones empleado', $th->getLine(), $th->getMessage()]);
            throw $th;
        }
    }
}
