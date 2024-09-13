<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use App\Models\Proveedor;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empresa|null $empresa
 * @property-read Proveedor|null $proveedor
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereApellidos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereCelular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereCorreo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereEmpresaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereNombres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereProveedorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereTipoContacto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContactoProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
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


    private static $whiteListFilter = ['*'];
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
