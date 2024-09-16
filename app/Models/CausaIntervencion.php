<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\CausaIntervencion
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property int $tipo_trabajo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\TipoTrabajo $tipoTrabajo
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereTipoTrabajoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CausaIntervencion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CausaIntervencion extends Model implements Auditable
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'causas_intervenciones';
    protected $fillable = ['nombre', 'activo', 'tipo_trabajo_id'];
    protected $casts = [
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        '*',
    ];

    public function tipoTrabajo()
    {
        return $this->belongsTo(TipoTrabajo::class);
    }
}
