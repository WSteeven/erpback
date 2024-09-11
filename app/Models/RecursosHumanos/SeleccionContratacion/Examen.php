<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Canton;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @method static create($datos)
 * @method static find(mixed $id)
 */
class Examen extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'rrhh_contratacion_examenes';
    protected $fillable = [
        'postulacion_id',
        'fecha_hora',
        'canton_id',
        'direccion',
        'laboratorio',
        'indicaciones',
        'se_realizo_examen',
        'es_apto',
        'observacion'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'se_realizo_examen' => 'boolean',
        'es_apto' => 'boolean',
    ];

    public function getKeyName()
    {
        return 'postulacion_id';
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable',null, null, 'postulacion_id');
    }
    /**
     * Relación uno a uno.
     * Un solo examen médico se emite para una postulación
     */
    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'postulacion_id');
    }
    public function canton()
    {
        return $this->belongsTo(Canton::class, 'canton_id');
    }

}
