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
