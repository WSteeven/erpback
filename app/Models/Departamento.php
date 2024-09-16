<?php

namespace App\Models;

use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

/**
 * App\Models\Departamento
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property int|null $responsable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Proveedor> $calificaciones_proveedores
 * @property-read int|null $calificaciones_proveedores_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CategoriaOfertaProveedor> $categorias_proveedores
 * @property-read int|null $categorias_proveedores_count
 * @property-read \App\Models\Empleado|null $responsable
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento query()
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereResponsableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Departamento whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Departamento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const DEPARTAMENTO_SSO = 5;
    const DEPARTAMENTO_CONTABILIDAD = 'CONTABILIDAD';
    const DEPARTAMENTO_GERENCIA = 'GERENCIA';
    const DEPARTAMENTO_RRHH = 'RECURSOS HUMANOS';

    protected $table = 'departamentos';
    protected $fillable = ['nombre', 'activo', 'responsable_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static $whiteListFilter = [
        'nombre',
        'activo',
        'responsable_id',
    ];

    /**
     * ______________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________
     */
    public function responsable()
    {
        return $this->belongsTo(Empleado::class, 'responsable_id', 'id');
    }
    public function calificaciones_proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'detalle_departamento_proveedor', 'departamento_id', 'proveedor_id')
            ->withPivot(['calificacion', 'fecha_calificacion'])
            ->withTimestamps();
    }
    public function categorias_proveedores()
    {
        return $this->belongsToMany(CategoriaOfertaProveedor::class, 'cmp_detalle_categoria_departamento_proveedor', 'departamento_id', 'categoria_id')
            ->withTimestamps();
    }
}
