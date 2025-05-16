<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Medico\CategoriaExamenFisico
 *
 * @property int $id
 * @property string $nombre
 * @property int $region_cuerpo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Medico\RegionCuerpo|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico query()
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico whereRegionCuerpoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CategoriaExamenFisico whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CategoriaExamenFisico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel, Filterable;

    const CICATRICES = 1;
    const TATUAJES = 2;
    const PIEL_FANERAS = 3;
    const PARPADOS = 4;
    const CONJUNTIVAS = 5;
    const PUPILAS = 6;
    const CORNEA = 7;
    const MOTILIDAD = 8;
    const AUDITIVO_EXTERNO = 9;
    const PABELLON = 10;
    const TIMPANOS = 11;
    const LABIOS = 12;
    const LENGUA = 13;
    const FARINGE = 14;
    const AMIGDALAS = 15;
    const DENTADURA = 16;
    const TABIQUE = 17;
    const CORNETES = 18;
    const MUCOSAS = 19;
    const SENOS_PARANASALES = 20;
    const TIROIDES_MASAS = 21;
    const MOVILIDAD = 22;
    const MAMAS = 23;
    const CORAZON = 24;
    const PULMONES = 25;
    const PARRILLA_COSTAL = 26;
    const VISCERAS = 27;
    const PARED_ABDOMINAL = 28;
    const FLEXIBILIDAD = 29;
    const DESVIACION = 30;
    const DOLOR = 31;
    const PELVIS = 32;
    const GENITALES = 33;
    const VASCULAR = 34;
    const MIEMBROS_SUPERIORES = 35;
    const MIEMBROS_INFERIORES = 36;
    const FUERZA = 37;
    const SENSIBILIDAD = 38;
    const MARCHA = 39;
    const REFLEJOS = 40;

    protected $table = 'med_categorias_examenes_fisicos';
    protected $fillable = [
        'nombre',
        'region_cuerpo_id'
    ];
    private static $whiteListFilter = ['*'];

    public function region()
    {
        return $this->belongsTo(RegionCuerpo::class, 'region_cuerpo_id', 'id');
    }
}
