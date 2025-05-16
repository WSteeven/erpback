<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class PrendaZona extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'seg_prendas_zonas';
    protected $fillable = [
        'detalles_productos',
        'tiene_restricciones',
        'zona_id',
        'empleado_id',
        'cliente_id',
    ];

    private static array $whiteListFilter = ['*'];
    protected $casts = [
        'tiene_restricciones' => 'boolean',
    ];

    /**************
     * Relaciones
     **************/
    public function zona()
    {
        return $this->belongsTo(Zona::class);
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
