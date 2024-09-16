<?php

namespace App\Models\RecursosHumanos\SeleccionContratacion;

use App\Models\Cargo;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\SeleccionContratacion\BancoPostulante
 *
 * @method static filter()
 * @method static create(mixed $datos)
 * @method static where(string $string, $user_id)
 * @property mixed $user_type
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $bancable
 * @property-read Cargo|null $cargo
 * @property-read Postulacion|null $postulacion
 * @method static Builder|BancoPostulante acceptRequest(?array $request = null)
 * @method static Builder|BancoPostulante ignoreRequest(?array $request = null)
 * @method static Builder|BancoPostulante newModelQuery()
 * @method static Builder|BancoPostulante newQuery()
 * @method static Builder|BancoPostulante query()
 * @method static Builder|BancoPostulante setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|BancoPostulante setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|BancoPostulante setLoadInjectedDetection($load_default_detection)
 * @mixin Eloquent
 */
class BancoPostulante extends Model implements Auditable
{
    use HasFactory, AuditableModel, UppercaseValuesTrait, Filterable;

    protected $table = 'rrhh_contratacion_bancos_postulantes';
    protected $fillable = [
        'user_id',
        'user_type',
        'cargo_id',
        'postulacion_id',
        'puntuacion',
        'observacion',
        'descartado', // cuando se consulta a la persona y no tiene disponibilidad para ese trabajo en ese momento y ya no desea ser contactado para futuras ofertas
        'fue_contactado', // si resulta contactado 3  veces y no hay disponibilidad se descarta automaticamente
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'descartado' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function bancable(): MorphTo
    {
        return $this->morphTo();
    }

    public function postulacion()
    {
        return $this->belongsTo(Postulacion::class, 'postulacion_id');
    }
    public  function cargo(){
        return $this->belongsTo(Cargo::class);
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
