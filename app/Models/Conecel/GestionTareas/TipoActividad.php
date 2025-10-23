<?php

namespace App\Models\Conecel\GestionTareas;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class TipoActividad extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'claro_tar_tipos_actividades';
    protected $fillable = ['nombre', 'activo'];

    private static array $whiteListFilter = [
        '*',
    ];





}
