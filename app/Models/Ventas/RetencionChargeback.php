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

/**
 * App\Models\Ventas\RetencionChargeback
 *
 * @property int $id
 * @property int|null $venta_id
 * @property int|null $vendedor_id
 * @property string $fecha_retencion
 * @property float $valor_retenido
 * @property int $pagado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @property-read \App\Models\Ventas\Venta|null $venta
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback query()
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereFechaRetencion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback wherePagado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereValorRetenido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereVendedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RetencionChargeback whereVentaId($value)
 * @mixin \Eloquent
 */
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

    public static function crearRetencionesChargebackCorte(Vendedor $vendedor, $ventas, $fecha_inicio, $fecha_fin)
    {
        try {
            DB::beginTransaction();
            if ($ventas->count() > 0) {
                foreach ($ventas as $venta) {
                    Log::channel('testing')->info('Log', ['venta en foreach', $venta]);
                    $ventaEnRetencion = RetencionChargeback::where('venta_id', $venta->id)->where('vendedor_id', $vendedor->empleado_id)->first();
                    if (!$ventaEnRetencion) {
                        RetencionChargeback::create([
                            'venta_id' => $venta->id,
                            'vendedor_id' => $vendedor->empleado_id,
                            'fecha_retencion' => Carbon::now(),
                            'valor_retenido' => $venta->comision_vendedor * .1
                        ]);
                    }
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
