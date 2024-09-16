<?php

namespace App\Models\ComprasProveedores;


use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\ComprasProveedores\CriterioCalificacion|null $criterio_calificacion
 * @property-read \App\Models\ComprasProveedores\DetalleDepartamentoProveedor|null $departamento_proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereCalificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereCriterioCalificacionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereDetalleDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor wherePuntaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CalificacionDepartamentoProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
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


    private static $whiteListFilter = ['*'];

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
