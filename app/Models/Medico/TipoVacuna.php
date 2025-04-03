<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\TipoVacuna
 *
 * @property int $id
 * @property string $nombre
 * @property int $dosis_totales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna query()
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna whereDosisTotales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TipoVacuna whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TipoVacuna extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    protected $table = 'med_tipos_vacunas';
    protected $fillable = [
        'nombre',
        'dosis_totales',
    ];

    private static $whiteListFilter = ['*'];
}
