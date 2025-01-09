<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class HorarioLaboral extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_horario_laboral';

    protected $fillable = [
        'hora_entrada',
        'hora_salida',
        'tipo_horario',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
        'tipo_horario' => 'string',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    public function getHoraEntradaAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    public function getHoraSalidaAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('H:i');
    }

    private static array $whiteListFilter = [
        '*',
    ];
}
