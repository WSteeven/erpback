<?php

namespace App\Models\Seguridad;

use App\Models\DetalleProducto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class RestriccionPrendaZona extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'seg_restricciones_prendas_zonas';
    protected $fillable = [
        'detalle_producto_id',
        'miembro_zona_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function miembroZona()
    {
        return $this->belongsTo(MiembroZona::class);
    }

    public function detalleProducto()
    {
        return $this->belongsTo(DetalleProducto::class);
    }

    public static function tieneRestriccion(int $miembro_zona_id)
    {
        return (RestriccionPrendaZona::where('miembro_zona_id', $miembro_zona_id)->exists());
    }
}
