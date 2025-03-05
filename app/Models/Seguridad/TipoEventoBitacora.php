<?php

namespace App\Models\Seguridad;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class TipoEventoBitacora extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'seg_tipos_eventos_bitacoras';
    protected $fillable = [
        'nombre',
        'descripcion',
        'notificacion_inmediata',
        'activo',
    ];

    private static array $whiteListFilter = ['*'];
    protected $casts = [
        'notificacion_inmediata' => 'boolean',
        'activo' => 'boolean',
    ];
}
