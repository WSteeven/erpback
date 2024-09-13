<?php

namespace App\Models\Bodega;

use App\Models\DetalleProducto;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;


/**
 * App\Models\Bodega\PermisoArma
 *
 * @property int $id
 * @property string $nombre
 * @property string $fecha_emision
 * @property string $fecha_caducidad
 * @property string|null $imagen_permiso
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $imagen_permiso_reverso
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read DetalleProducto|null $detalle
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereFechaCaducidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereFechaEmision($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereImagenPermiso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereImagenPermisoReverso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermisoArma whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = [
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
