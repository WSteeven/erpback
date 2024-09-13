<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empresa|null $empresa
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereCostoTransporte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereEmpresaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereEnvios($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereGarantia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereTiempoEntrega($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereTipoEnvio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereTransporteIncluido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LogisticaProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
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

    private static $whiteListFilter = ['*'];
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
