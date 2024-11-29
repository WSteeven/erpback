<?php

namespace App\Models\RecursosHumanos\Capacitacion;

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
        'nombre',
        'formulario',
        'es_recurrente',
        'mes_inicia',
        'tipo', //interna,externa
        'activa',
    ];

    protected $casts= [
        'formulario' => 'array'
    ];
}
