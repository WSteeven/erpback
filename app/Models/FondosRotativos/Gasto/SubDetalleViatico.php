<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\FondosRotativos\Usuario\Estatus;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static where(string $string, mixed $subdetalle)
 */
class SubDetalleViatico extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'sub_detalle_viatico';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_detalle_viatico',
        'descripcion',
        'autorizacion',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
        'tiene_factura'
    ];
    protected $casts = [
        'tiene_factura' => 'boolean',
    ];
    private static $whiteListFilter = [
        'descripcion',
    ];
    public function detalle()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'id_detalle_viatico');
    }
    public function estatus()
    {
        return $this->hasOne(Estatus::class, 'id', 'id_estatus');
    }
    public function gastos()
    {
        return $this->belongsToMany(Gasto::class, 'subdetalle_gastos', 'detalle', 'id');
    }
}
