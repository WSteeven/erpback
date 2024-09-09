<?php

namespace App\Models\ActivosFijos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class CategoriaMotivoConsumoActivoFijo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'af_categorias_motivos_consumo_activos_fijos';
    protected $fillable = [
        'nombre',
    ];

    private static $whiteListFilter = ['*'];
}
