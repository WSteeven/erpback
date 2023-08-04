<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Familiares extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    const  ESPOSO= 'ESPOSO';
    const ESPOSA = 'ESPOSA';
    const HIJO = 'HIJO';
    const HIJA = 'HIJA';
    protected $table = 'egreso_rol_pago';
    protected $fillable = [
        'identificacion',
        'parentezco',
        'nombres',
        'nombres'
    ];

    private static $whiteListFilter = [
        'id',
        'identificacion',
        'parentezco',
        'nombres',
        'nombres'
    ];
}
