<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\RecursosHumanos\SeleccionContratacion\UserExternal|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante query()
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereNumeroDocumentoIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereTipoDocumentoIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Postulante whereUsuarioExternalId($value)
 * @mixin \Eloquent
 */
class Postulante extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
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
        'usuario_external_id'
    ];
    private static $whiteListFilter = [
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
}
