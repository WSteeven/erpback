<?php

namespace App\Models;

use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Departamento
 *
 * @property int $id
 * @property string $nombre
 * @property bool $activo
 * @property int|null $responsable_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Proveedor> $calificaciones_proveedores
 * @property-read int|null $calificaciones_proveedores_count
 * @property-read Collection<int, CategoriaOfertaProveedor> $categorias_proveedores
 * @property-read int|null $categorias_proveedores_count
 * @property-read Empleado|null $responsable
 * @method static Builder|Departamento acceptRequest(?array $request = null)
 * @method static Builder|Departamento filter(?array $request = null)
 * @method static Builder|Departamento ignoreRequest(?array $request = null)
 * @method static Builder|Departamento newModelQuery()
 * @method static Builder|Departamento newQuery()
 * @method static Builder|Departamento query()
 * @method static Builder|Departamento setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Departamento setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Departamento setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Departamento whereActivo($value)
 * @method static Builder|Departamento whereCreatedAt($value)
 * @method static Builder|Departamento whereId($value)
 * @method static Builder|Departamento whereNombre($value)
 * @method static Builder|Departamento whereResponsableId($value)
 * @method static Builder|Departamento whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Departamento extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    const DEPARTAMENTO_SSO = 5;
    const DEPARTAMENTO_CONTABILIDAD = 'CONTABILIDAD';
    const DEPARTAMENTO_FINANCIERO = 'FINANCIERO';
    const DEPARTAMENTO_GERENCIA = 'GERENCIA';
    const DEPARTAMENTO_MEDICO = 'MEDICO';
    const DEPARTAMENTO_RRHH = 'RECURSOS HUMANOS';
    const DEPARTAMENTO_TRABAJO_SOCIAL = 'TRABAJO SOCIAL';

    protected $table = 'departamentos';
    protected $fillable = ['nombre', 'activo', 'responsable_id', 'telefono', 'correo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        'nombre',
        'activo',
        'responsable_id',
        'telefono',
        'correo',
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
