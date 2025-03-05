<?php

namespace App\Models\Seguridad;

use App\Models\Empleado;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class MiembroZona extends Model implements Auditable
{
    use HasFactory, Filterable, AuditableModel;

    protected $table = 'seg_miembros_zonas';
    protected $fillable = [
        'zona_id',
        'empleado_id',
    ];

    private static array $whiteListFilter = ['*'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
