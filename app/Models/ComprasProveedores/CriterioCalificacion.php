<?php

namespace App\Models\ComprasProveedores;

use App\Models\Departamento;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\ComprasProveedores\CriterioCalificacion
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property float $ponderacion_referencia
 * @property int|null $departamento_id
 * @property int|null $oferta_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, DetalleDepartamentoProveedor> $calificaciones_criterios
 * @property-read int|null $calificaciones_criterios_count
 * @property-read Departamento|null $departamento
 * @property-read OfertaProveedor|null $oferta
 * @method static Builder|CriterioCalificacion acceptRequest(?array $request = null)
 * @method static Builder|CriterioCalificacion filter(?array $request = null)
 * @method static Builder|CriterioCalificacion ignoreRequest(?array $request = null)
 * @method static Builder|CriterioCalificacion newModelQuery()
 * @method static Builder|CriterioCalificacion newQuery()
 * @method static Builder|CriterioCalificacion query()
 * @method static Builder|CriterioCalificacion setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CriterioCalificacion setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CriterioCalificacion setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CriterioCalificacion whereCreatedAt($value)
 * @method static Builder|CriterioCalificacion whereDepartamentoId($value)
 * @method static Builder|CriterioCalificacion whereDescripcion($value)
 * @method static Builder|CriterioCalificacion whereId($value)
 * @method static Builder|CriterioCalificacion whereNombre($value)
 * @method static Builder|CriterioCalificacion whereOfertaId($value)
 * @method static Builder|CriterioCalificacion wherePonderacionReferencia($value)
 * @method static Builder|CriterioCalificacion whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CriterioCalificacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;


    protected $table = 'criterios_calificaciones';
    protected $fillable = [
        'nombre',
        'descripcion',
        'ponderacion_referencia',
        'departamento_id',
        'oferta_id',
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

     public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }



    public function oferta()
    {
        return $this->belongsTo(OfertaProveedor::class);
    }

    public function calificaciones_criterios(){
        return $this->belongsToMany(DetalleDepartamentoProveedor::class, 'calificacion_departamento_proveedor', 'detalle_departamento_id', 'criterio_calificacion_id')
        ->withPivot('comentario', 'peso', 'puntaje', 'calificacion')->withTimestamps();
    }
}
