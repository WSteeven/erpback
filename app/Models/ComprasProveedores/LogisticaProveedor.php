<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class LogisticaProveedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use AuditableModel;
    use Filterable;

    protected $table = 'cmp_logisticas_proveedores';
    protected $fillable = [
        'tiempo_entrega',
        'envios',
        'tipo_envio',
        'transporte_incluido',
        'costo_transporte',
        'garantia',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'envios' => 'boolean',
        'transporte_incluido' => 'boolean',
        'garantia' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
