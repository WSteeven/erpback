<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Models\Pais;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @method static find(mixed $postulacion)
 * @method static ignoreRequest(string[] $array)
 * @method static where(string $string, mixed $id)
 */
class Postulacion extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_postulaciones';

    protected $fillable = [
        'mi_experiencia',
        'vacante_id',
        'direccion',
        'pais_residencia_id',
        'tengo_conocimientos_requeridos',
        'tengo_disponibilidad_viajar',
        'tengo_documentos_regla',
        'tengo_experiencia_requerida',
        'tengo_formacion_academica_requerida',
        'tengo_licencia_conducir',
        'tipo_licencia',
        'calificacion',
        'activo',
        'user_id',
        'user_type',
        'ruta_cv',
        'leido_rrhh',
        'estado',
        'dado_alta',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'tengo_conocimientos_requeridos' => 'boolean',
        'tengo_disponibilidad_viajar' => 'boolean',
        'tengo_documentos_regla' => 'boolean',
        'tengo_experiencia_requerida' => 'boolean',
        'tengo_formacion_academica_requerida' => 'boolean',
        'tengo_licencia_conducir' => 'boolean',
        'leido_rrhh' => 'boolean',
        'activo' => 'boolean',
        'dado_alta' => 'boolean',
    ];
    // --------------------------------
    // ESTADOS
    // --------------------------------
    const POSTULADO = 'POSTULADO';
    const REVISION_CV = 'REVISION CV';
    const ENTREVISTA = 'EN ENTREVISTA';
    const DESCARTADO = 'DESCARTADO';
    const PRESELECCIONADO = 'PRESELECCIONADO';
    const SELECCIONADO = 'SELECCIONADO';
    const EXAMENES_MEDICOS = 'EXAMENES MEDICOS';
    const CONTRATADO = 'CONTRATADO';
    const BANCO_DE_CANDIDATOS = 'BANCO DE CANDIDATOS';
    const RECHAZADO = 'RECHAZADO';

    // -------------------------------------
    // CALIFICACIONES
    // -------------------------------------
    const NO_CALIFICADO = 'NO CALIFICADO';
//    const ALTA_PRIORIDAD = 'ALTA PRIORIDAD';
//    const BAJA_PRIORIDAD = 'BAJA PRIORIDAD';
    const NO_CONSIDERAR = 'NO CONSIDERAR';

    private static array $whiteListFilter = ['*'];

    public function vacante()
    {
        return $this->belongsTo(Vacante::class);
    }

    public function paisResidencia()
    {
        return $this->belongsTo(Pais::class, 'pais_residencia_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Una postulacion puede tener 0 o 1 entrevista
     * @return HasOne
     */
    public function entrevista()
    {
        return $this->hasOne(Entrevista::class, 'postulacion_id', 'id');
    }

    /**
     * Relación uno a uno.
     * Una postulacion puede tener 0 o 1 examenes
     * @return HasOne
     */
    public function examen()
    {
        return $this->hasOne(Examen::class, 'postulacion_id', 'id');
    }

    /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivable');
    }


    public function postulacionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        // Determina el tipo de usuario autenticado
        if ($this->user_type === User::class) {
            return $this->belongsTo(User::class, 'user_id', 'id');
        }
        if ($this->user_type === UserExternal::class) {
            return $this->belongsTo(UserExternal::class, 'user_id', 'id');
        }
        return [];
    }
}
