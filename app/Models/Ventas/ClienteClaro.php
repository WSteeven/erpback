<?php

namespace App\Models\Ventas;

use App\Models\Canton;
use App\Models\Parroquia;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use OwenIt\Auditing\Models\Audit;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ClienteClaro acceptRequest(?array $request = null)
 * @method static Builder|ClienteClaro filter(?array $request = null)
 * @method static Builder|ClienteClaro ignoreRequest(?array $request = null)
 * @method static Builder|ClienteClaro newModelQuery()
 * @method static Builder|ClienteClaro newQuery()
 * @method static Builder|ClienteClaro query()
 * @method static Builder|ClienteClaro setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ClienteClaro setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ClienteClaro setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ClienteClaro whereActivo($value)
 * @method static Builder|ClienteClaro whereApellidos($value)
 * @method static Builder|ClienteClaro whereCreatedAt($value)
 * @method static Builder|ClienteClaro whereDireccion($value)
 * @method static Builder|ClienteClaro whereId($value)
 * @method static Builder|ClienteClaro whereIdentificacion($value)
 * @method static Builder|ClienteClaro whereNombres($value)
 * @method static Builder|ClienteClaro whereSupervisorId($value)
 * @method static Builder|ClienteClaro whereTelefono1($value)
 * @method static Builder|ClienteClaro whereTelefono2($value)
 * @method static Builder|ClienteClaro whereUpdatedAt($value)
 * @mixin Eloquent
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
        'estado_id',
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

    public function estado()
    {
        return $this->belongsTo(EstadoClaro::class);
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


    private static array $whiteListFilter = [
        '*',
    ];
}
