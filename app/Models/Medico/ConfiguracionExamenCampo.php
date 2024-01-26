<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class ConfiguracionExamenCampo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_configuraciones_examenes_campos';
    protected $fillable = [
        'campo',
        'unidad_medida',
        'intervalo_referencia',
        'configuracion_examen_categoria_id',
    ];

    private static $whiteListFilter = ['*'];

    public function configuracionExamenCategoria()
    {
        return $this->hasOne(ConfiguracionExamenCategoria::class, 'id', 'configuracion_examen_categoria_id');
    }
}
