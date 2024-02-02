<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        try {
            DB::beginTransaction();
            $ventas = Venta::where(function ($query) use ($fecha_inicio, $fecha_fin) {
                $query->whereBetween('fecha_activacion', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
                // ->orWhereBetween('fecha_pago_primer_mes', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))]);
            })->where('estado_activacion', Venta::ACTIVADO)->where('vendedor_id', $vendedor->empleado_id)->get();
            foreach ($ventas as $venta) {
                RetencionChargeback::create([
                    'venta_id' => $venta->id,
                    'vendedor_id' => $vendedor->empleado_id,
                    'fecha_retencion' => Carbon::now(),
                    'valor_retenido' => $venta->comision * .1
                ]);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
