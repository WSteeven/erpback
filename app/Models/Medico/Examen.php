<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ExamenFilter;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Medico\Examen
 *
 * @property int $id
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, CategoriaExamen> $categoria
 * @property-read int|null $categoria_count
 * @property-read Collection<int, EstadoSolicitudExamen> $estadoSolicitudExamen
 * @property-read int|null $estado_solicitud_examen_count
 * @property-read Collection<int, TipoExamen> $tipoExamen
 * @property-read int|null $tipo_examen_count
 * @method static Builder|Examen acceptRequest(?array $request = null)
 * @method static Builder|Examen filter(?array $request = null)
 * @method static Builder|Examen ignoreRequest(?array $request = null)
 * @method static Builder|Examen newModelQuery()
 * @method static Builder|Examen newQuery()
 * @method static Builder|Examen query()
 * @method static Builder|Examen setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Examen setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Examen setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Examen whereCreatedAt($value)
 * @method static Builder|Examen whereId($value)
 * @method static Builder|Examen whereNombre($value)
 * @method static Builder|Examen whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Examen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel, ExamenFilter;

    protected $table = 'med_examenes';
    protected $fillable = [
        'nombre',
    ];
    private static array $whiteListFilter = ['*', 'empleado_id', 'registro_empleado_examen_id'];

    /*************
     * Relaciones
     *************/
    public function categoria()
    {
        return $this->belongsToMany(CategoriaExamen::class, 'med_detalles_examenes', 'examen_id', 'categoria_examen_id')->withTimestamps(); ///withPivot('cantidad')->withTimestamps();
    }

    public function tipoExamen()
    {
        return $this->belongsToMany(TipoExamen::class, 'med_detalles_examenes', 'examen_id', 'tipo_examen_id')->withTimestamps(); ///withPivot('cantidad')->withTimestamps();
    }

    public function estadoSolicitudExamen()
    {
        return $this->hasMany(EstadoSolicitudExamen::class);
    }
}
