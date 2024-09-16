<?php

namespace App\Models\ComprasProveedores;

use App\Models\Empresa;
use App\Models\RecursosHumanos\Banco;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\ComprasProveedores\DatoBancarioProveedor
 *
 * @property int $id
 * @property int|null $banco_id
 * @property int|null $empresa_id
 * @property string $tipo_cuenta
 * @property string $numero_cuenta
 * @property string|null $identificacion
 * @property string|null $nombre_propietario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Banco|null $banco
 * @property-read Empresa|null $empresa
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor query()
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereBancoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereEmpresaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereIdentificacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereNombrePropietario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereNumeroCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereTipoCuenta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DatoBancarioProveedor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DatoBancarioProveedor extends Model implements Auditable 
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'cmp_datos_bancarios_proveedores';

    protected $fillable =[
        'banco_id',
        'empresa_id',
        'tipo_cuenta',
        'numero_cuenta',
        'identificacion',
        'nombre_propietario',
    ];

    //Tipos de cuenta
    const AHORROS = 'AHORROS';
    const CORRIENTE= 'CORRIENTE';

    private static $whiteListFilter = ['*'];
    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }
    public function banco(){
        return $this->belongsTo(Banco::class);
    }




}
