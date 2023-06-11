<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class HorasExtraSubTipo extends Model  implements Auditable
{
    use HasFactory;
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'horas_extra_sub_tipos';
    protected $fillable = [
        'nombre','hora_extra_id'
    ];

    private static $whiteListFilter = [
        'id',
        'nombre',
        'hora_extra'
    ];
    public function horas_extras_info()
    {
        return $this->hasOne(HorasExtraTipo::class, 'id', 'hora_extra_id');
    }
}
