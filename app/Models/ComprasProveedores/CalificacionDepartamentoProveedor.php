<?php

namespace App\Models\ComprasProveedores;


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
 * App\Models\ComprasProveedores\CalificacionDepartamentoProveedor
 *
 * @property int $id
 * @property int|null $detalle_departamento_id
 * @property int|null $criterio_calificacion_id
 * @property string|null $comentario
 * @property int $peso
 * @property int $puntaje
 * @property float $calificacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read CriterioCalificacion|null $criterio_calificacion
 * @property-read DetalleDepartamentoProveedor|null $departamento_proveedor
 * @method static Builder|CalificacionDepartamentoProveedor acceptRequest(?array $request = null)
 * @method static Builder|CalificacionDepartamentoProveedor filter(?array $request = null)
 * @method static Builder|CalificacionDepartamentoProveedor ignoreRequest(?array $request = null)
 * @method static Builder|CalificacionDepartamentoProveedor newModelQuery()
 * @method static Builder|CalificacionDepartamentoProveedor newQuery()
 * @method static Builder|CalificacionDepartamentoProveedor query()
 * @method static Builder|CalificacionDepartamentoProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CalificacionDepartamentoProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CalificacionDepartamentoProveedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CalificacionDepartamentoProveedor whereCalificacion($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereComentario($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereCreatedAt($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereCriterioCalificacionId($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereDetalleDepartamentoId($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereId($value)
 * @method static Builder|CalificacionDepartamentoProveedor wherePeso($value)
 * @method static Builder|CalificacionDepartamentoProveedor wherePuntaje($value)
 * @method static Builder|CalificacionDepartamentoProveedor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CalificacionDepartamentoProveedor extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use Filterable;
    use AuditableModel;

    protected $table = 'calificacion_departamento_proveedor';
    protected $fillable = [
        'detalle_departamento_id',
        'criterio_calificacion_id',
        'comentario',
        'peso',
        'puntaje',
        'calificacion',
    ];


    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relacion uno a muchos (inversa).
     * Una calificacion se realiza en base a un departamento calificador de un proveedor.
     */
    public function departamento_proveedor()
    {
        return $this->belongsTo(DetalleDepartamentoProveedor::class);
    }

    /**
     * Relacion uno a muchos (inversa).
     * Una calificacion se realiza en base a un criterio
     */
    public function criterio_calificacion()
    {
        return $this->belongsTo(CriterioCalificacion::class);
    }
}
