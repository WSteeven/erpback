<?php

namespace App\Models\RecursosHumanos\Capacitacion;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Formulario extends Model implements  Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'rrhh_cap_formularios';
    protected $fillable = [
        'empleado_id',
        'nombre',
        'formulario',
        'es_recurrente',
        'periodo_recurrencia', //expresado en meses
        'fecha_inicio',
        'tipo', //interna,externa
        'activo',
    ];

    protected $casts= [
        'formulario' => 'array',
        'es_recurrente' => 'boolean',
        'activo' => 'boolean',
    ];

    const INTERNO = 'INTERNO';
    const  EXTERNO = 'EXTERNO';

    public function empleado(){
        return $this->belongsTo(Empleado::class);
    }


}
