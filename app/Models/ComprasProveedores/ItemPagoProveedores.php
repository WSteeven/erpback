<?php

namespace App\Models\ComprasProveedores;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ItemPagoProveedores extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;

    public $table = 'cmp_item_pago_proveedor';
    public $fillable = [
        'pago_proveedor_id',
        'proveedor',
        'razon_social',
        'tipo_documento',
        'num_documento',
        'fecha_emision',
        'fecha_vencimiento',
        'centro_costo',
        'plazo',
        'total',
        'descripcion',
        'valor_documento',
        'valor_pagar',
        'retenciones',
        'pagos',
    ];

    const POR_VENCER = 'POR VENCER';
    const TREINTA_DIAS = '30 DIAS';
    const SESENTA_DIAS = '60 DIAS';
    const NOVENTA_DIAS = '90 DIAS';
    const CIENTO_VEINTE_DIAS = '120 DIAS';
    const MAYOR_TIEMPO = 'MAYOR A 120 DIAS';

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function pago()
    {
        return $this->belongsTo(PagoProveedores::class, 'id', 'pago_proveedor_id');
    }
}
