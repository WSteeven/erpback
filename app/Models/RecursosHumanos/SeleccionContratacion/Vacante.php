<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class Vacante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'rrhh_contratacion_vacantes';

    //Agregar la modalidad

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_caducidad',
        'imagen_referencia',
        'imagen_publicidad',
        'anios_experiencia',
        'areas_conocimiento',
        'numero_postulantes',
        'tipo_puesto_id',
        'publicante_id',
        'solicitud_id',
        'activo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function tipoPuesto()
    {
        return $this->hasOne(TipoPuesto::class, 'id', 'tipo_puesto_id');
    }

    public function publicante()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function solicitud()
    {
        return $this->belongsTo(SolicitudPersonal::class);
    }

    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    public function favorita()
    {
        return $this->morphMany(Favorita::class, 'favoritable', 'user_type', 'user_id');
    }

}
