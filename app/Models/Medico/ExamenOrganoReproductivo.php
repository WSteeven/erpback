<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class ExamenOrganoReproductivo extends Model implements Auditable
{
    use HasFactory;
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_examenes_organos_reproductivos';
    protected $fillable = [
        'examen',
        'tipo', //M-F
    ];
    private static $whiteListFilter = ['*'];
}
