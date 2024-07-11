<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


class Vacante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'rrhh_contratacion_vacantes';
    protected $fillable = [
        'nombre',
        'descripcion',
        'anios_experiencia',
        'tipo_puesto_id',
        'autorizador_id',
        'autorizacion_id',
        'cargo_id',
    ];
    
    private static $whiteListFilter = ['*'];

}
