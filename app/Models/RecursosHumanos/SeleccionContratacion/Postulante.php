<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * @method static find($user_id)
 * @method static create(mixed $datos)
 */
class Postulante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;
    const CEDULA = 'CEDULA';
    const RUC = 'RUC';
    const PASAPORTE = 'PASAPORTE';
    protected $table = 'rrhh_postulantes';
    protected $fillable = [
        'nombres',
        'apellidos',
        'correo_personal',
        'direccion',
        'fecha_nacimiento',
        'genero',
        'identidad_genero_id',
        'pais_id',
        'tipo_documento_identificacion',
        'numero_documento_identificacion',
        'telefono',
        'usuario_external_id'
    ];
    private static array $whiteListFilter = [
        'nombres',
        'apellidos',
        'tipo_documento_identificacion',
        'numero_documento_identificacion',
        'telefono',
        'usuario_external_id',
        'usuario_external'
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    public function usuario()
    {
        return $this->hasOne(UserExternal::class, 'id', 'usuario_external_id');
    }

    public static function extraerNombresApellidos(Postulante $persona)
    {
        return $persona->nombres . ' ' . $persona->apellidos;
    }
}
