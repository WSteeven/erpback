<?php

namespace App\Models\ComprasProveedores;

use App\Models\Departamento;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\CriterioCalificacion
 *
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 * @property float $ponderacion_referencia
 * @property int|null $departamento_id
 * @property int|null $oferta_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ComprasProveedores\DetalleDepartamentoProveedor> $calificaciones_criterios
 * @property-read int|null $calificaciones_criterios_count
 * @property-read Departamento|null $departamento
 * @property-read \App\Models\ComprasProveedores\OfertaProveedor|null $oferta
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion query()
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereDepartamentoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereDescripcion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereOfertaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion wherePonderacionReferencia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CriterioCalificacion whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];

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
