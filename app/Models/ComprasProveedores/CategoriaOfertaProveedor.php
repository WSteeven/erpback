<?php

namespace App\Models\ComprasProveedores;

use App\Models\Departamento;
use App\Models\Proveedor;
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
 * App\Models\ComprasProveedores\CategoriaOfertaProveedor
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $tipo_oferta_id
 * @property bool $estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, Departamento> $departamentos_responsables
 * @property-read int|null $departamentos_responsables_count
 * @property-read OfertaProveedor|null $oferta
 * @method static Builder|CategoriaOfertaProveedor acceptRequest(?array $request = null)
 * @method static Builder|CategoriaOfertaProveedor filter(?array $request = null)
 * @method static Builder|CategoriaOfertaProveedor ignoreRequest(?array $request = null)
 * @method static Builder|CategoriaOfertaProveedor newModelQuery()
 * @method static Builder|CategoriaOfertaProveedor newQuery()
 * @method static Builder|CategoriaOfertaProveedor query()
 * @method static Builder|CategoriaOfertaProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CategoriaOfertaProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CategoriaOfertaProveedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CategoriaOfertaProveedor whereCreatedAt($value)
 * @method static Builder|CategoriaOfertaProveedor whereEstado($value)
 * @method static Builder|CategoriaOfertaProveedor whereId($value)
 * @method static Builder|CategoriaOfertaProveedor whereNombre($value)
 * @method static Builder|CategoriaOfertaProveedor whereTipoOfertaId($value)
 * @method static Builder|CategoriaOfertaProveedor whereUpdatedAt($value)
 * @property-read Collection<int, Proveedor> $categorias_proveedores
 * @property-read int|null $categorias_proveedores_count
 * @mixin Eloquent
 */
class CategoriaOfertaProveedor extends Model implements Auditable
{
    use HasFactory;
    use Filterable;
    use UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'cmp_categorias_ofertas_proveedores';
    public $fillable = [
        'nombre',
        'tipo_oferta_id',
        'estado',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a muchos.
     * Una o muchas categoria pertenece a un tipo de oferta
     */
    public function oferta()
    {
        return $this->belongsTo(OfertaProveedor::class, 'tipo_oferta_id', 'id');
    }

    public function categorias_proveedores(){
        return $this->belongsToMany(Proveedor::class, 'detalle_categoria_proveedor','categoria_id','proveedor_id')
        ->withTimestamps();
    }

    /**
     * Relación muchos a muchos.
     */
    public function departamentos_responsables()
    {
        return $this->belongsToMany(Departamento::class, 'cmp_detalle_categoria_departamento_proveedor', 'categoria_id', 'departamento_id')
            ->withTimestamps();
    }
}
