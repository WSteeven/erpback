<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static create(mixed $datos)
 */
class Entrevista extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_entrevistas';

    protected $fillable = [
        'postulacion_id',
        'fecha_hora',
        'duracion',
        'reagendada', //boolean
        'nueva_fecha_hora',
        'observacion',
        'asistio' //boolean
    ];

    //obtener la llave primaria
    public function getKeyName()
    {
        return 'postulacion_id';
    }

    /**
     * Relación uno a uno.
     * Una entrevista se emite para una postulación
     */
    public function postulacion(){
        return $this->belongsTo(Postulacion::class, 'postulacion_id');
    }
}
