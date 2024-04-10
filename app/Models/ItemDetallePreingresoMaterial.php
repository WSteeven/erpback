<?php

namespace App\Models;

use App\Models\FondosRotativos\Gasto\Gasto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ItemDetallePreingresoMaterial extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = 'item_detalle_preingreso_material';
    protected $fillable = [
        'preingreso_id',
        'detalle_id',
        'descripcion',
        'cantidad',
        'serial',
        'punta_inicial',
        'punta_final',
        'unidad_medida_id',
        'condicion_id',
        'fotografia',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = [
        '*',
    ];


    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedida::class);
    }
    
    public function condicion()
    {
        return $this->belongsTo(Condicion::class);
    }
}
