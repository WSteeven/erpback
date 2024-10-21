<?php

namespace App\Models\ComprasProveedores;

use App\Models\Pais;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\ProveedorInternacional
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $tipo
 * @property string|null $ruc
 * @property int $pais_id
 * @property string|null $direccion
 * @property string|null $telefono
 * @property string|null $correo
 * @property string|null $sitio_web
 * @property bool $activo
 * @property string|null $banco1
 * @property string|null $numero_cuenta1
 * @property string|null $codigo_swift1
 * @property string|null $moneda1
 * @property string|null $banco2
 * @property string|null $numero_cuenta2
 * @property string|null $codigo_swift2
 * @property string|null $moneda2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Pais $pais
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereActivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereBanco1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereBanco2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereCodigoSwift1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereCodigoSwift2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereDireccion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereMoneda1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereMoneda2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereNumeroCuenta1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereNumeroCuenta2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional wherePaisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereRuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereSitioWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProveedorInternacional whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProveedorInternacional extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable, UppercaseValuesTrait;

    protected $table = 'cmp_proveedores_internacionales';

    protected $fillable = [
        'nombre',
        'tipo', //persona juridica o natural
        'ruc',
        'pais_id',
        'direccion',
        'telefono',
        'correo',
        'sitio_web',
        'banco1',
        'numero_cuenta1',
        'codigo_swift1',
        'moneda1',
        'banco2',
        'numero_cuenta2',
        'codigo_swift2',
        'moneda2',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }
}
