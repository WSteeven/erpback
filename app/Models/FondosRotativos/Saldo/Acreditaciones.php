<?php

namespace App\Models\FondosRotativos\Saldo;

use App\Models\Empleado;
use App\Models\FondosRotativos\Gasto\TipoFondo;
use App\Traits\UppercaseValuesTrait;
use Database\Factories\FondosRotativos\Saldo\AcreditacionesFactory;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\Saldo\Acreditaciones
 *
 * @property int $id
 * @property string $fecha
 * @property string $id_saldo
 * @property string $descripcion_acreditacion
 * @property string|null $motivo
 * @property string $monto
 * @property int $id_usuario
 * @property int $id_tipo_saldo
 * @property int $id_tipo_fondo
 * @property int $id_estado
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read EstadoAcreditaciones|null $estado
 * @property-read Collection<int, Saldo> $saldoFondoRotativo
 * @property-read int|null $saldo_fondo_rotativo_count
 * @property-read TipoFondo|null $tipoFondo
 * @property-read TipoSaldo|null $tipoSaldo
 * @property-read Empleado|null $usuario
 * @method static Builder|Acreditaciones acceptRequest(?array $request = null)
 * @method static AcreditacionesFactory factory($count = null, $state = [])
 * @method static Builder|Acreditaciones filter(?array $request = null)
 * @method static Builder|Acreditaciones ignoreRequest(?array $request = null)
 * @method static Builder|Acreditaciones newModelQuery()
 * @method static Builder|Acreditaciones newQuery()
 * @method static Builder|Acreditaciones query()
 * @method static Builder|Acreditaciones setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Acreditaciones setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Acreditaciones setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Acreditaciones whereCreatedAt($value)
 * @method static Builder|Acreditaciones whereDescripcionAcreditacion($value)
 * @method static Builder|Acreditaciones whereFecha($value)
 * @method static Builder|Acreditaciones whereId($value)
 * @method static Builder|Acreditaciones whereIdEstado($value)
 * @method static Builder|Acreditaciones whereIdSaldo($value)
 * @method static Builder|Acreditaciones whereIdTipoFondo($value)
 * @method static Builder|Acreditaciones whereIdTipoSaldo($value)
 * @method static Builder|Acreditaciones whereIdUsuario($value)
 * @method static Builder|Acreditaciones whereMonto($value)
 * @method static Builder|Acreditaciones whereMotivo($value)
 * @method static Builder|Acreditaciones whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Acreditaciones extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    protected $table = 'acreditaciones';
    protected $fillable = [
        'id_tipo_fondo',
        'id_tipo_saldo',
        'id_usuario',
        'id_saldo',
        'fecha',
        'descripcion_acreditacion',
        'monto',
        'id_estado',
    ];
    private static array $whiteListFilter = [
        'fecha',
        'id_estado',
    ];
    public function usuario()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user','canton');
    }
    public function estado(){
        return $this->hasOne(EstadoAcreditaciones::class, 'id', 'id_estado');
    }
    public function tipoSaldo(){
        return $this->hasOne(TipoSaldo::class, 'id', 'id_tipo_saldo');
    }
    public function tipoFondo(){
        return $this->hasOne(TipoFondo::class, 'id', 'id_tipo_fondo');
    }

    /**
     * @throws Exception
     */
    public static function empaquetar($acreditaciones)
    {
        try {
            $results = [];
            $id = 0;
            $row = [];
            if (isset($acreditaciones)) {
                foreach ($acreditaciones as $acreditacion) {
                    $row['item'] = $id + 1;
                    $row['id'] = $acreditacion->id;
                    $row['fecha'] = $acreditacion->fecha;
                    $row['tipo_saldo'] = $acreditacion->tipoSaldo->descripcion;
                    $row['tipo_fondo'] = $acreditacion->tipoFondo->descripcion;
                    $row['usuario'] = $acreditacion->usuario->user;
                    $row['cargo'] = $acreditacion->usuario->cargo==null?'':$acreditacion->usuario->cargo->nombre;
                    $row['empleado'] = $acreditacion->usuario;
                    $row['descripcion_acreditacion'] = $acreditacion->descripcion_acreditacion;
                    $row['monto'] = $acreditacion->monto;
                    $results[$id] = $row;
                    $id++;
                }

            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error Acreditaciones::empaquetar', $e->getMessage(), $e->getLine()]);
            throw $e;
        }
        return $results;

    }
    public function saldoFondoRotativo()
    {
        return $this->morphMany(Saldo::class, 'saldoable');
    }
}
