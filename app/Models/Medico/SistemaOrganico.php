<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class SistemaOrganico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const PIEL_ANEXOS = 1;
    const ORGANOS_DE_LOS_SENTIDOS = 2;
    const RESPIRATORIO = 3;
    const CARDIOVASCULAR = 4;
    const DIGESTIVO = 5;
    const GENITO_URINARIO = 6;
    const MUSCULO_ESQUELETICO = 7;
    const ENDOCRINO = 8;
    const HEMOLINFATICO = 9;
    const NERVIOSO = 10;

    protected $table = 'med_sistemas_organicos';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];
}
