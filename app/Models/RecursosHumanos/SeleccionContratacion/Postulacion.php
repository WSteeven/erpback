<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Archivo;
use App\Models\Pais;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


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
        'activo',
        'user_id',
        'user_type',
        'ruta_cv',
        'leido_rrhh',
        'estado',
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
        'activo' => 'boolean',
    ];
    // --------------------------------
    // ESTADOS
    // --------------------------------
    const POSTULADO = 'POSTULADO';

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
    }
}
