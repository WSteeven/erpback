<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CuestionarioPublico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_cuestionarios_publicos'; // med_respuestas_cuestionarios_empleados
    protected $fillable = [
        'respuesta_texto',
        'cuestionario_id',
        'persona_id',
        // 'respuesta', // id
    ];
    private static $whiteListFilter = ['*'];

    /*************
     * Relaciones
     *************/
    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id')->with('pregunta');
    }
}
