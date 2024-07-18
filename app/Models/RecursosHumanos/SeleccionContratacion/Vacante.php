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
    private static array $whiteListFilter = ['*'];

    protected $fillable = [
        'nombre',
        'tipo_puesto_id',
        'imagen_referencia',
        'imagen_publicidad',
        'fecha_caducidad',
        'descripcion',
        'anios_experiencia',
        'areas_conocimiento',
        'solicitante_id',
        'publicante_id',
        'solicitud_id',
    ];

}
