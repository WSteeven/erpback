<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
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
 * App\Models\ComprasProveedores\ContactoProveedor
 *
 * @property int $id
 * @property string|null $nombres
 * @property string|null $apellidos
 * @property string|null $celular
 * @property string|null $ext
 * @property string|null $correo
 * @property string $tipo_contacto
 * @property int|null $empresa_id
 * @property int|null $proveedor_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empresa|null $empresa
 * @property-read Proveedor|null $proveedor
 * @method static Builder|ContactoProveedor acceptRequest(?array $request = null)
 * @method static Builder|ContactoProveedor filter(?array $request = null)
 * @method static Builder|ContactoProveedor ignoreRequest(?array $request = null)
 * @method static Builder|ContactoProveedor newModelQuery()
 * @method static Builder|ContactoProveedor newQuery()
 * @method static Builder|ContactoProveedor query()
 * @method static Builder|ContactoProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|ContactoProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|ContactoProveedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|ContactoProveedor whereApellidos($value)
 * @method static Builder|ContactoProveedor whereCelular($value)
 * @method static Builder|ContactoProveedor whereCorreo($value)
 * @method static Builder|ContactoProveedor whereCreatedAt($value)
 * @method static Builder|ContactoProveedor whereEmpresaId($value)
 * @method static Builder|ContactoProveedor whereExt($value)
 * @method static Builder|ContactoProveedor whereId($value)
 * @method static Builder|ContactoProveedor whereNombres($value)
 * @method static Builder|ContactoProveedor whereProveedorId($value)
 * @method static Builder|ContactoProveedor whereTipoContacto($value)
 * @method static Builder|ContactoProveedor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ContactoProveedor extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'cmp_contactos_proveedores';
    protected $fillable = [
        "nombres",
        "apellidos",
        "celular",
        "ext",
        "correo",
        "tipo_contacto",
        "empresa_id",
        "proveedor_id",
    ];

    const TECNICO = 'TECNICO'; //contacto tecnico
    const FINANCIERO = 'FINANCIERO'; //contacto financiero
    const COMERCIAL = 'COMERCIAL'; //contacto comercial
    // const WEB = 'SITIO WEB'; //contacto SITIO WEB


    private static array $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

}
