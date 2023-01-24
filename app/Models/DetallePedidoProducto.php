<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class DetallePedidoProducto extends Pivot implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'detalle_pedido_producto';
    protected $fillable = [
        'detalle_id',
        'pedido_id',
        'cantidad',
        'despachado',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];




}
