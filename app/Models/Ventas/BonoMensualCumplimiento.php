<?php

namespace App\Models\Ventas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * App\Models\Ventas\BonoMensualCumplimiento
 *
 * @property int $id
 * @property int|null $vendedor_id
 * @property int $cant_ventas
 * @property string $mes
 * @property string $valor
 * @property int $pagada
 * @property string|null $bonificable_type
 * @property int|null $bonificable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|\Eloquent $bonificable
 * @property-read \App\Models\Ventas\Bono|null $bono
 * @property-read \App\Models\Ventas\Vendedor|null $vendedor
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento acceptRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento ignoreRequest(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereBonificableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereBonificableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereCantVentas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento wherePagada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereValor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonoMensualCumplimiento whereVendedorId($value)
 * @mixin \Eloquent
 */
class BonoMensualCumplimiento extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel, Filterable;
    protected $table = 'ventas_bonos_mensuales_cumplimientos';
    protected $fillable = [
        'vendedor_id',
        'cant_ventas',
        'mes',
        'bono_id',
        'bono_type',
        'valor',
    ];
    private static $whiteListFilter = [
        '*',
    ];
    public function vendedor()
    {
        return $this->hasOne(Vendedor::class, 'empleado_id', 'vendedor_id')->with('empleado');
    }
    public function bono()
    {
        return $this->hasOne(Bono::class, 'id', 'bono_id');
    }

    //Relación polimorfica
    public function bonificable()
    {
        return $this->morphTo();
        // return $this->morphTo(__FUNCTION__, 'bono_type', 'bono_id');
    }

    /**
     * La función `crearBonoCumplimiento` crea un registro de cumplimiento de bonificación para una
     * determinada entidad, vendedor, número de ventas, fecha y valor.
     * 
     * @param mixed $entidad El parámetro `entidad` en la función `crearBonoCumplimiento` parece representar
     * un objeto de entidad. Este objeto se utiliza para crear un nuevo registro en la relación
     * `bonosCumplimiento` de la entidad.
     * @param int $vendedor_id El parámetro `vendedor_id` en la función `crearBonoCumplimiento` representa
     * el ID del vendedor asociado con el bono de cumplimiento que se está creando. Esta identificación
     * se utiliza para vincular el registro de cumplimiento de bonificación con el vendedor específico
     * en la base de datos.
     * @param int $cant_ventas El parámetro `cant_ventas` representa el número de ventas realizadas por el
     * vendedor para el cual se está creando el bonoCumplimiento. Este valor indica el nivel de
     * consecución o cumplimiento de los objetivos de ventas que ha alcanzado el vendedor.
     * @param string|Date|Carbon $fecha El parámetro `fecha` en la función `crearBonoCumplimiento` parece representar el
     * mes para el cual se está creando el bono de cumplimiento. Probablemente sea una fecha o marca de
     * tiempo que indica el mes en el que se aplica el bono.
     * @param int|float $valor El parámetro `valor` en la función `crearBonoCumplimiento` representa el valor del
     * bono que se crea por cumplir ciertos criterios como objetivos de ventas. Este valor podría ser
     * una cantidad monetaria o cualquier otra forma de incentivo que se le esté otorgando al vendedor
     * (vendedor).
     * 
     * @return La función `crearBonoCumplimiento` devuelve el objeto `ón` recién creado, que
     * representa un registro de cumplimiento de bonificación creado para una entidad, vendedor, número
     * de ventas, mes y valor específicos.
     */
    public static function crearBonoCumplimiento($entidad, $vendedor_id, $cant_ventas, $fecha,  $valor)
    {
        try {
            DB::beginTransaction();
            //Validacion de datos

            $rules = [
                'vendedor_id' => 'required|exists:ventas_vendedores,empleado_id|unique:ventas_bonos_mensuales_cumplimientos,vendedor_id,NULL,id,mes,' . $fecha->format('Y-m'),
                'mes' => 'required|unique:ventas_bonos_mensuales_cumplimientos,vendedor_id', // Se requiere que sea una fecha con formato 'Y-m-d' (año-mes-día)
                'cant_ventas' => 'required|numeric', // Se requiere que sea un entero
                'valor' => 'required|numeric', // Se requiere que sea un número
            ];
            $validator = Validator::make([
                'vendedor_id' => $vendedor_id,
                'cant_ventas' => $cant_ventas,
                'mes' => $fecha,
                'valor' => $valor,
            ], $rules);

            if ($validator->fails()) {
                // La validación ha fallado
                // foreach ($validator->errors()->all() as $error) {
                //     Log::channel('testing')->info('Log', ['error', $error]);
                //     throw ValidationException::withMessages([
                //         'Error' => ['Ya existe un registro del mismo vendedor en la misma fecha'],
                //     ]);
                // }
                // throw $errors;
                $bono=null;
            } else {
                // La validación ha pasado exitosamente
                // Procede con tu lógica aquí

                $bono = $entidad->bonosCumplimiento()->create([
                    'vendedor_id' => $vendedor_id,
                    'cant_ventas' => $cant_ventas,
                    'mes' =>  $fecha,
                    'valor' =>  $valor,
                ]);
            }
            DB::commit();
            return $bono;
        } catch (Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage() . '. [LINE CODE ERROR]: ' . $th->getLine(), $th->getCode());
        }
    }
}
