<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\RecursosHumanos\SeleccionContratacion\ReferenciaPersonal
 *
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $referenciable
 * @method static Builder|ReferenciaPersonal acceptRequest(?array $request = null)
 * @method static Builder|ReferenciaPersonal filter(?array $request = null)
 * @method static Builder|ReferenciaPersonal ignoreRequest(?array $request = null)
 * @method static Builder|ReferenciaPersonal newModelQuery()
 * @method static Builder|ReferenciaPersonal newQuery()
 * @method static Builder|ReferenciaPersonal query()
 * @method static Builder|ReferenciaPersonal setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ReferenciaPersonal setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ReferenciaPersonal setLoadInjectedDetection($load_default_detection)
 * @property int $id
 * @property int $user_id
 * @property string $user_type
 * @property string $nombres_apellidos
 * @property string $cargo
 * @property string|null $telefono
 * @property string|null $correo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|ReferenciaPersonal whereCargo($value)
 * @method static Builder|ReferenciaPersonal whereCorreo($value)
 * @method static Builder|ReferenciaPersonal whereCreatedAt($value)
 * @method static Builder|ReferenciaPersonal whereId($value)
 * @method static Builder|ReferenciaPersonal whereNombresApellidos($value)
 * @method static Builder|ReferenciaPersonal whereTelefono($value)
 * @method static Builder|ReferenciaPersonal whereUpdatedAt($value)
 * @method static Builder|ReferenciaPersonal whereUserId($value)
 * @method static Builder|ReferenciaPersonal whereUserType($value)
 * @mixin Eloquent
 */
class ReferenciaPersonal extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'rrhh_contratacion_referencias_personales';
    protected $fillable = [
        'user_id',
        'user_type',
        'nombres_apellidos',
        'cargo',
        'telefono',
        'correo'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];
    private static array $whiteListFilter = ['*'];

    public function referenciable():MorphTo
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
