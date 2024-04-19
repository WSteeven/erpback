<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class CategoriaFactorRiesgo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_categorias_factores_riesgos';
    protected $fillable = [
        'nombre',
        'tipo_factor_riesgo_id',
    ];
    private static $whiteListFilter = ['*'];

    public function tipo()
    {
        return $this->hasOne(TipoFactorRiesgo::class);
    }
}
