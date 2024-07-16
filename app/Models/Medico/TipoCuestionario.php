<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class TipoCuestionario extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_cuestionarios';
    protected $fillable = [
        'titulo',
    ];
    private static $whiteListFilter = ['*'];

    // Tipos de cuestionarios
    const CUESTIONARIO_PSICOSOCIAL = 1;
    const CUESTIONARIO_DIAGNOSTICO_CONSUMO_DE_DROGAS = 2;
}
