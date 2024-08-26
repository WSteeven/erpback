<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Empleado;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use Src\Shared\ObtenerInstanciaUsuario;

/**
 * @method static whereIn(string $string, $ids_favoritas)
 * @method static find($vacante_id)
 * @method static ignoreRequest(string[] $array)
 * @method static create(mixed $datos)
 */
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
        'fecha_caducidad',
        'imagen_referencia',
        'imagen_publicidad',
        'anios_experiencia',
        'areas_conocimiento',
        'numero_postulantes',
        'tipo_puesto_id',
        'modalidad_id',
        'publicante_id',
        'solicitud_id',
        'activo',
        'disponibilidad_viajar',
        'requiere_licencia',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
        'disponibilidad_viajar' => 'boolean',
        'requiere_licencia' => 'boolean',
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

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class);
    }

    public function formacionesAcademicas()
    {
        return $this->morphMany(FormacionAcademica::class, 'formacionable');
    }

    public function favorita()
    {
        [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

        // Si hay un usuario autenticado, retorna una instancia de la relación
        return $this->hasOne(Favorita::class, 'vacante_id')
            ->where('user_id', $user_id)
            ->where('user_type', $user_type);
    }

    public function postulacion()
    {

        [$user_id, $user_type] = ObtenerInstanciaUsuario::tipoUsuario();

        return $this->hasOne(Postulacion::class, 'vacante_id')
            ->where('user_id', $user_id)
            ->where('user_type', $user_type);
    }
}
