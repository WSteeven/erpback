<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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


    public function crearComisionesEmpleados($fecha_inicio, $fecha_fin)
    {
        $ventas = Venta::whereBetween('fecha_activacion', [date('Y-m-d', strtotime($fecha_inicio)), date('Y-m-d', strtotime($fecha_fin))])
            ->where('estado_activacion', Venta::ACTIVADO)->where('primer_mes', true)->get();
    }
}
