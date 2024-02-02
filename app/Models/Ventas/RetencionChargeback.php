<?php

namespace App\Models\Ventas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
