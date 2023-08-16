<?php

namespace App\Models\ComprasProveedores;

use App\Models\UnidadMedida;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ItemDetalleProforma extends Model implements Auditable
{
    use HasFactory, AuditableModel;
    public $table = 'cmp_item_detalle_proforma';
    public $fillable = [
        'proforma_id',
        'unidad_medida_id',
        'descripcion',
        'cantidad',
        'porcentaje_descuento',
        'descuento',
        'facturable',
        'grava_iva',
        'precio_unitario',
        'iva',
        'subtotal',
        'total',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function proforma(){
        return $this->belongsTo(Proforma::class);
    }

    /**
     * Relacion uno a muchos
     */
    public function unidadMedida(){
        return $this->belongsTo(UnidadMedida::class);
    }

}
