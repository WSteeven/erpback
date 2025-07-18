<?php

namespace App\Models\Bodega;

use App\Models\DetalleProductoTransaccion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetalleProductoTransaccionLote extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;

    protected $table = 'detalle_producto_transaccion_lote';
    protected $fillable = [
        'detalle_producto_id',
        'lote_id',
        'cantidad',
    ];

    public function lote(){
        return $this->belongsTo(Lote::class);
    }

    public function detalleProductoTransaccion()
    {
        return $this->belongsTo(DetalleProductoTransaccion::class, 'detalle_producto_id');
    }
}
