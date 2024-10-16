<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\Postulante
 *
 * @property int $id
 * @property string $nombres
 * @property string $apellidos
 * @property string $tipo_documento_identificacion
 * @property string $numero_documento_identificacion
 * @property string $telefono
 * @property int $usuario_external_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read UserExternal|null $usuario
 * @method static Builder|Postulante acceptRequest(?array $request = null)
 * @method static Builder|Postulante filter(?array $request = null)
 * @method static Builder|Postulante ignoreRequest(?array $request = null)
 * @method static Builder|Postulante newModelQuery()
 * @method static Builder|Postulante newQuery()
 * @method static Builder|Postulante query()
 * @method static Builder|Postulante setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Postulante setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Postulante setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Postulante whereApellidos($value)
 * @method static Builder|Postulante whereCreatedAt($value)
 * @method static Builder|Postulante whereId($value)
 * @method static Builder|Postulante whereNombres($value)
 * @method static Builder|Postulante whereNumeroDocumentoIdentificacion($value)
 * @method static Builder|Postulante whereTelefono($value)
 * @method static Builder|Postulante whereTipoDocumentoIdentificacion($value)
 * @method static Builder|Postulante whereUpdatedAt($value)
 * @method static Builder|Postulante whereUsuarioExternalId($value)
 * @property string|null $correo_personal
 * @property string|null $direccion
 * @property string|null $fecha_nacimiento
 * @property string|null $genero
 * @property int|null $identidad_genero_id
 * @property int|null $pais_id
 * @method static Builder|Postulante whereCorreoPersonal($value)
 * @method static Builder|Postulante whereDireccion($value)
 * @method static Builder|Postulante whereFechaNacimiento($value)
 * @method static Builder|Postulante whereGenero($value)
 * @method static Builder|Postulante whereIdentidadGeneroId($value)
 * @method static Builder|Postulante wherePaisId($value)
 * @mixin Eloquent
 */
class Postulante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    const CEDULA = 'CEDULA';
    CONST RUC = 'RUC';
    CONST PASAPORTE = 'PASAPORTE';
    protected $table = 'rrhh_postulantes';
    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_documento_identificacion',
        'numero_documento_identificacion',
        'telefono',
        'correo_personal',
        'direccion',
        'fecha_nacimiento',
        'genero',
        'identidad_genero_id',
        'pais_id',
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

    public function usuario(){
        return $this->hasOne(UserExternal::class,'id', 'usuario_external_id');
    }

    public static function extraerNombresApellidos(Postulante|null $persona){
     if(is_null($persona)) return null;
     return $persona->nombres.' '.$persona->apellidos;
    }
}
