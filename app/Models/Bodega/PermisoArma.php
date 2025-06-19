<?php

namespace App\Models\Bodega;

use App\Models\DetalleProducto;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;


/**
 * App\Models\Bodega\PermisoArma
 *
 * @property int $id
 * @property string $nombre
 * @property string $fecha_emision
 * @property string $fecha_caducidad
 * @property string|null $imagen_permiso
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $imagen_permiso_reverso
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleProducto|null $detalle
 * @method static Builder|PermisoArma acceptRequest(?array $request = null)
 * @method static Builder|PermisoArma filter(?array $request = null)
 * @method static Builder|PermisoArma ignoreRequest(?array $request = null)
 * @method static Builder|PermisoArma newModelQuery()
 * @method static Builder|PermisoArma newQuery()
 * @method static Builder|PermisoArma query()
 * @method static Builder|PermisoArma setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|PermisoArma setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|PermisoArma setLoadInjectedDetection($load_default_detection)
 * @method static Builder|PermisoArma whereCreatedAt($value)
 * @method static Builder|PermisoArma whereFechaCaducidad($value)
 * @method static Builder|PermisoArma whereFechaEmision($value)
 * @method static Builder|PermisoArma whereId($value)
 * @method static Builder|PermisoArma whereImagenPermiso($value)
 * @method static Builder|PermisoArma whereImagenPermisoReverso($value)
 * @method static Builder|PermisoArma whereNombre($value)
 * @method static Builder|PermisoArma whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PermisoArma extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'bod_permisos_armas';
    protected $fillable = [
        'nombre',
        'fecha_emision',
        'fecha_caducidad',
        'imagen_permiso',
        'imagen_permiso_reverso',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a uno (inversa).
     * Un Permiso de arma puede estar asignado a 0 o 1 DetalleProducto
     */
    public function detalle()
    {
        return $this->belongsTo(DetalleProducto::class);
    }
}
