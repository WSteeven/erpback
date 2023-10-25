<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class AcreditacionSemana extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'fr_acreditacion_semana';
    protected $primaryKey = 'id';
    protected $fillable = [
        'semana',
        'acreditar',
    ];
    private static $whiteListFilter = [
        'semana',
        'acreditar',
    ];
    protected $casts = [
        'acreditar' => 'boolean',
    ];
}
