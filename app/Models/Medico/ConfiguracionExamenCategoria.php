<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ConfiguracionExamenCategoria extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_configuraciones_examenes_categorias';
    protected $fillable = [
        'nombre',
        'examen_id',
    ];

    private static $whiteListFilter = ['*'];

    public function examen()
    {
        return $this->hasOne(Examen::class, 'id', 'examen_id');
    }
}
