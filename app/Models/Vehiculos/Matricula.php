<?php

namespace App\Models\Vehiculos;

use App\Models\Notificacion;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\Vehiculos\Matricula
 *
 * @method static where(string $string, false $false)
 * @property int $id
 * @property int|null $vehiculo_id
 * @property string|null $fecha_matricula
 * @property string|null $proxima_matricula
 * @property float|null $valor_estimado_pagar
 * @property string|null $matriculador
 * @property bool $matriculado
 * @property string|null $fecha_pago
 * @property string|null $observacion
 * @property float|null $monto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Notificacion|null $latestNotificacion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read \App\Models\Vehiculos\Vehiculo|null $vehiculo
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereFechaMatricula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereFechaPago($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereMatriculado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereMatriculador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereMonto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereProximaMatricula($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereValorEstimadoPagar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereVehiculoId($value)
 * @mixin \Eloquent
 */
class Matricula extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, Filterable, UppercaseValuesTrait;


    protected $table = 'veh_matriculas';
    protected $fillable = [
        'vehiculo_id',
        'fecha_matricula',
        'proxima_matricula',
        'valor_estimado_pagar',
        'fecha_pago',
        'matriculador',
        'matriculado',
        'observacion',
        'monto',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'matriculado' => 'boolean',
    ];

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    /**
     * Relación uno a muchos (inversa).
     * Una o varias multas pertenecen a un Conductor.
     */
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Una matricula puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */
    public static function crearMatricula($vehiculo_id, $fecha_matricula, $proxima_matricula)
    {
        try {
            DB::beginTransaction();
            $matricula = Matricula::create([
                'vehiculo_id' => $vehiculo_id,
                'fecha_matricula' => $fecha_matricula,
                'proxima_matricula' => $proxima_matricula,
            ]);
            DB::commit();
            return $matricula;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
