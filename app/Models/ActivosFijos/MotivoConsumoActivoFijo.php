<?php

namespace App\Models\ActivosFijos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

class MotivoConsumoActivoFijo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'af_motivos_consumo_activos_fijos';
    protected $fillable = [
        'nombre',
        'categoria_motivo_consumo_activo_fijo_id',
    ];

    private static $whiteListFilter = ['*'];
}
