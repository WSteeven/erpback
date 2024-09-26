<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
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
 * App\Models\ComprasProveedores\LogisticaProveedor
 *
 * @property int $id
 * @property int|null $empresa_id
 * @property string|null $tiempo_entrega
 * @property bool $envios
 * @property string|null $tipo_envio
 * @property bool $transporte_incluido
 * @property string|null $costo_transporte
 * @property bool $garantia
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empresa|null $empresa
 * @method static Builder|LogisticaProveedor acceptRequest(?array $request = null)
 * @method static Builder|LogisticaProveedor filter(?array $request = null)
 * @method static Builder|LogisticaProveedor ignoreRequest(?array $request = null)
 * @method static Builder|LogisticaProveedor newModelQuery()
 * @method static Builder|LogisticaProveedor newQuery()
 * @method static Builder|LogisticaProveedor query()
 * @method static Builder|LogisticaProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|LogisticaProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|LogisticaProveedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|LogisticaProveedor whereCostoTransporte($value)
 * @method static Builder|LogisticaProveedor whereCreatedAt($value)
 * @method static Builder|LogisticaProveedor whereEmpresaId($value)
 * @method static Builder|LogisticaProveedor whereEnvios($value)
 * @method static Builder|LogisticaProveedor whereGarantia($value)
 * @method static Builder|LogisticaProveedor whereId($value)
 * @method static Builder|LogisticaProveedor whereTiempoEntrega($value)
 * @method static Builder|LogisticaProveedor whereTipoEnvio($value)
 * @method static Builder|LogisticaProveedor whereTransporteIncluido($value)
 * @method static Builder|LogisticaProveedor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LogisticaProveedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use AuditableModel;
    use Filterable;

    protected $table = 'cmp_logisticas_proveedores';
    protected $fillable = [
        'tiempo_entrega',
        'envios',
        'tipo_envio',
        'transporte_incluido',
        'costo_transporte',
        'garantia',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'envios' => 'boolean',
        'transporte_incluido' => 'boolean',
        'garantia' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
