<?php

namespace App\Models\Tareas;

use App\Models\DetalleProducto;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleTransferenciaProductoEmpleado extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable;
    protected $table = 'tar_det_tran_prod_emp';

    protected $fillable = [
        'detalle_producto_id',
        'transf_produc_emplea_id',
        'cantidad',
        'cliente_id',
    ];

    private static $whiteListFilter = ['*'];

    /**************
     * Relaciones
     **************/
    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
    public function transferencia(){
        return $this->belongsTo(TransferenciaProductoEmpleado::class, 'transf_produc_emplea_id');
    }
}
