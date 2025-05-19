<?php

namespace App\Models\Ventas;

use App\Models\Canton;
use App\Models\Parroquia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Ventas\ClienteClaro
 *
 * @property int $id
 * @property int|null $supervisor_id
 * @property string $identificacion
 * @property string $nombres
 * @property string $apellidos
 * @property string $direccion
 * @property string $telefono1
 * @property string|null $telefono2
 * @property string $canton_id
 * @property string $parroquia_id
 * @property string $tipo_cliente
 * @property string $correo_electronico
 * @property string $foto_cedula_frontal
 * @property string $foto_cedula_posterior
 * @property string $fecha_expedicion_cedula
 * @property bool $activo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereSupervisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereTelefono1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereTelefono2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClienteClaro whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClienteClaro extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, UppercaseValuesTrait, Filterable;
    protected $table = 'ventas_clientes_claro';
    protected $fillable = [
        'supervisor_id',
        'identificacion',
        'nombres',
        'apellidos',
        'direccion',
        'telefono1',
        'telefono2',
        'canton_id',
        'parroquia_id',
        'tipo_cliente',
        'correo_electronico',
        'foto_cedula_frontal',
        'foto_cedula_posterior',
        'fecha_expedicion_cedula',
        'activo'
    ];

    /**
     * --------------------------------------------------------------------------
     * RELACIONES CON OTRAS TABLAS
     * --------------------------------------------------------------------------
     */
    public function supervisor()
    {
        return $this->belongsTo(Vendedor::class, 'supervisor_id');
    }
    public function canton()
    {
        return $this->belongsTo(Canton::class, 'canton_id');
    }
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'parroquia_id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];


    private static $whiteListFilter = [
        '*',
    ];
}
