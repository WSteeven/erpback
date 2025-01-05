<?php

namespace App\Models\FondosRotativos;

use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\FondosRotativos\AjusteSaldoFondoRotativo
 *
 * @method static whereBetween(Expression $raw, array $array)
 * @method static where(string $string, string $string1)
 * @property int $id
 * @property int $solicitante_id
 * @property int $destinatario_id
 * @property int $autorizador_id
 * @property string $motivo
 * @property string $descripcion
 * @property float $monto
 * @property string $tipo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $autorizador
 * @property-read Empleado|null $destinatario
 * @property-read Saldo|null $saldoFondoRotativo
 * @property-read Empleado|null $solicitante
 * @method static Builder|AjusteSaldoFondoRotativo acceptRequest(?array $request = null)
 * @method static Builder|AjusteSaldoFondoRotativo filter(?array $request = null)
 * @method static Builder|AjusteSaldoFondoRotativo ignoreRequest(?array $request = null)
 * @method static Builder|AjusteSaldoFondoRotativo newModelQuery()
 * @method static Builder|AjusteSaldoFondoRotativo newQuery()
 * @method static Builder|AjusteSaldoFondoRotativo query()
 * @method static Builder|AjusteSaldoFondoRotativo setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|AjusteSaldoFondoRotativo setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|AjusteSaldoFondoRotativo setLoadInjectedDetection($load_default_detection)
 * @method static Builder|AjusteSaldoFondoRotativo whereAutorizadorId($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereCreatedAt($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereDescripcion($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereDestinatarioId($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereId($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereMonto($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereMotivo($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereSolicitanteId($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereTipo($value)
 * @method static Builder|AjusteSaldoFondoRotativo whereUpdatedAt($value)
 * @mixin Eloquent
 */
class AjusteSaldoFondoRotativo extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;
    use Filterable;

    protected $table = 'fr_ajustes_saldos';
    protected $fillable = [
        'solicitante_id',
        'destinatario_id',
        'autorizador_id',
        'motivo',
        'descripcion',
        'monto',
        'tipo',
    ];

    const INGRESO = 'Ingreso';
    const EGRESO = 'Egreso';

    private static array $whiteListFilter = ['*'];

    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo pertenecen a un solicitante
     */
    public function solicitante()
    {
        return $this->belongsTo(Empleado::class, 'solicitante_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo van dirigidos a un destinatario
     */
    public function destinatario()
    {
        return $this->belongsTo(Empleado::class, 'destinatario_id', 'id');
    }
    /**
     * Relacion uno a muchos (inversa).
     * Uno o varios ajustes de saldo pertenecen a un autorizador
     */
    public function autorizador()
    {
        return $this->belongsTo(Empleado::class, 'autorizador_id', 'id');
    }
    public function saldoFondoRotativo()
    {
        return $this->morphOne(Saldo::class, 'saldoable');
    }

    /**
     * @throws Exception
     */
    public static function empaquetar($ajustessaldos)
    {
        try {
            $results = [];
            $id = 0;
            $row = [];
            foreach ($ajustessaldos as $ajustesaldo) {
                $row['id'] = $ajustesaldo->id;
                $row['num_registro'] = $id + 1;
                $row['fecha'] = $ajustesaldo->created_at;
                $row['solicitante'] = $ajustesaldo->solicitante->nombres . ' ' . $ajustesaldo->solicitante->apellidos;
                $row['destinatario'] = $ajustesaldo->destinatario->nombres . ' ' . $ajustesaldo->destinatario->apellidos;
                $row['motivo'] = $ajustesaldo->motivo;
                $row['descripcion'] = $ajustesaldo->descripcion;
                $row['monto'] = $ajustesaldo->monto;
                $results[$id] = $row;
                $id++;
            }
            return $results;
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error modelo', $e->getMessage(), $e->getLine()]);
            throw  $e;
        }
    }
}
