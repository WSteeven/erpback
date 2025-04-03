<?php

namespace App\Models\Medico;

use App\ModelFilters\Medico\ExamenFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\Medico\Examen
 *
 * @property int $id
 * @property string $nombre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\CategoriaExamen> $categoria
 * @property-read int|null $categoria_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\EstadoSolicitudExamen> $estadoSolicitudExamen
 * @property-read int|null $estado_solicitud_examen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Medico\TipoExamen> $tipoExamen
 * @property-read int|null $tipo_examen_count
 * @method static \Illuminate\Database\Eloquent\Builder|Examen acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Examen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Examen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Examen setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Examen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Examen extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel, ExamenFilter;

    protected $table = 'med_examenes';
    protected $fillable = [
        'nombre',
    ];
    private static $whiteListFilter = ['*'];

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
