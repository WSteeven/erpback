<?php

namespace App\Models\RecursosHumanos\ControlPersonal;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static Builder|HorarioLaboral where($column, $value)
 */
class HorarioLaboral extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_cp_horario_laboral';

    protected $fillable = [
        'nombre',
        'dia',
        'hora_entrada',
        'hora_salida',
        'inicio_pausa',
        'fin_pausa',
        'activo',
    ];

    protected $casts = [
        'hora_entrada' => 'datetime:H:i',
        'hora_salida' => 'datetime:H:i',
        'inicio_pausa' => 'datetime:H:i',
        'fin_pausa' => 'datetime:H:i',
        'activo' => 'boolean',
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

//    public function getHoraEntradaAttribute($value)
//    {
//        return \Carbon\Carbon::parse($value)->format('H:i');
//    }

//    public function getHoraSalidaAttribute($value)
//    {
//        return \Carbon\Carbon::parse($value)->format('H:i');
//    }

    private static array $whiteListFilter = [
        '*',
    ];
}
