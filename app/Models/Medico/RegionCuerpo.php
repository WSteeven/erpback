<?php

namespace App\Models\Medico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\RegionCuerpo
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\CategoriaExamenFisico> $categoriaExamen
 * @property-read int|null $categoria_examen_count
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo query()
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RegionCuerpo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RegionCuerpo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;
    protected $table = 'med_regiones_cuerpo';
    protected $fillable = [
        'nombre',
    ];
    public const PIEL = 1;
    public const OJOS = 2;
    public const OIDO = 3;
    public const OROFARINGUE = 4;
    public const NARIZ = 5;
    public const CUELLO = 6;
    public const TORAX = 7;
    public const ABDOMEN = 8;
    public const COLUMNA = 9;
    public const PELVIS = 10;
    public const EXTREMIDADES = 11;
    public const NEUROLOGICO = 12;

    private static $whiteListFilter = ['*'];

    public function categoriaExamen()
    {
        return $this->hasMany(CategoriaExamenFisico::class);
    }
}
