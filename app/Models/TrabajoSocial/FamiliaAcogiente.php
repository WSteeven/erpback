<?php

namespace App\Models\TrabajoSocial;

use App\Models\Canton;
use App\Models\RecursosHumanos\TrabajoSocial\Vivienda;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class FamiliaAcogiente extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'rrhh_ts_familias_acogientes';
    protected $fillable = [
        'vivienda_id',
        'canton_id',
        'parroquia_id',
        'tipo_parroquia',
        'direccion',
        'coordenadas',
        'referencia',
        'nombres_apellidos',
        'telefono',
    ];

    public function vivienda()
    {
        return $this->belongsTo(Vivienda::class);
    }

    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }


}
