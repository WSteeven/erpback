<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Models\Autorizacion;
use App\Models\Cargo;
use App\Models\Empleado;
use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static create($validated)
 * @method static ignoreRequest(string[] $array)
 * @property mixed $autorizacion_id
 * @property mixed $solicitante
 * @property mixed $solicitante_id
 * @property mixed $autorizador_id
 * @property mixed $autorizador
 * @property mixed $id
 */
class SolicitudPersonal extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    protected $table = 'rrhh_contratacion_solicitudes_nuevas_vacantes';

    protected $fillable = [
        'nombre',
        'publicada',
        'tipo_puesto_id',
        'solicitante_id',
        'autorizador_id',
        'autorizacion_id',
        'cargo_id',
        'anios_experiencia',
        'areas_conocimiento',
        'descripcion',

    ];
    private static array $whiteListFilter = ['*'];

    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuesto::class, 'id', 'tipo_puesto_id');
    }

    public function cargo()
    {
        return $this->hasOne(Cargo::class, 'id', 'cargo_id');
    }
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function autorizacion()
    {
        return $this->hasOne(Autorizacion::class, 'id', 'autorizacion_id');
    }
    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una orden de compra puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

}
