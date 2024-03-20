<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\ValorAcreditarRequest;
use App\Http\Resources\FondosRotativos\Saldo\ValorAcreditarResource;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\FondosRotativos\Saldo\ValorAcreditar;
use App\Models\FondosRotativos\UmbralFondosRotativos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ValorAcreditarController extends Controller
{
    private $entidad = 'Valor Acreditar';
    public function __construct()
    {
        $this->middleware('can:puede.ver.valor_acreditar')->only('index', 'show');
        $this->middleware('can:puede.crear.valor_acreditar')->only('store');
    }
    /**
     * La función de índice recupera y filtra los datos de ValorAcreditar en función de los parámetros de
     * la solicitud y devuelve los resultados en formato JSON.
     *
     * @param Request request El parámetro `Request ` en la función `index` es una instancia de la
     * clase Illuminate\Http\Request en Laravel. Representa la solicitud HTTP que se realiza al servidor.
     *
     * @return Una matriz que contiene los resultados de la consulta en el modelo `ValorAcreditar` donde la
     * columna `estado` es igual a 1, después de ignorar el campo 'campos' de la solicitud y aplicar
     * cualquier filtro adicional. Luego, los resultados se transforman en una colección de recursos
     * "ValorAcreditarResource" y se devuelven como una respuesta JSON con la clave "resultados".
     */
    public function index(Request $request)
    {
        $results = [];
        $results = ValorAcreditar::where('estado', 1)->ignoreRequest(['campos'])->filter()->get();
        $results = ValorAcreditarResource::collection($results);
        return response()->json(compact('results'));
    }
    /**
     * Esta función PHP recupera y devuelve una instancia actualizada de un modelo ValorAcreditar como
     * respuesta JSON.
     *
     * @param Request request El parámetro `` en la función `show` es una instancia de la clase
     * `Illuminate\Http\Request`. Representa la solicitud HTTP actual y contiene información como el método
     * de solicitud, encabezados y datos de entrada.
     * @param ValorAcreditar valor_acreditar La función `show` en el fragmento de código es un método que
     * toma dos parámetros: `` de tipo `Request` y `` de tipo `ValorAcreditar`.
     *
     * @return La función `show` devuelve una respuesta JSON que contiene la variable `results`, que es una
     * instancia de la clase `ValorAcreditarResource` creada a partir del objeto ``
     * actualizado. La respuesta incluye los datos de los "resultados" en un formato compacto.
     */
    public function show(Request $request, ValorAcreditar $valor_acreditar)
    {
        $results = $valor_acreditar;
        $results = new ValorAcreditarResource($results->refresh());
        return response()->json(compact('results'));
    }
    /**
     * La función `store` en PHP maneja el almacenamiento de datos de una solicitud en una transacción de
     * base de datos y devuelve una respuesta JSON con un mensaje de éxito o de error.
     *
     * @param ValorAcreditarRequest request La función "almacenar" que proporcionó parece estar manejando
     * el almacenamiento de un modelo "ValorAcreditar" basado en los datos validados de la solicitud
     * "ValorAcreditarRequest".
     *
     * @return La función `store` devuelve una respuesta JSON con la siguiente estructura:
     * - Si la operación tiene éxito:
     *   - Una clave `mensaje` que contiene un mensaje de éxito obtenido del método
     * `Utils::obtenerMensaje`.
     *   - Una clave `modelo` que contiene el recurso `ValorAcreditar` recién creado.
     * - Si ocurre una excepción:
     *   - Una respuesta JSON con un `mensaje`
     */
    public function store(ValorAcreditarRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $valoracreditar = ValorAcreditar::create($datos);
            $modelo = new ValorAcreditarResource($valoracreditar);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * Esta función PHP actualiza un registro en la base de datos utilizando Eloquent ORM de Laravel y
     * maneja excepciones con registros y mensajes de error.
     *
     * @param ValorAcreditarRequest request La función "actualizar" que proporcionó parece estar
     * actualizando una instancia del modelo "ValorAcreditar" basada en los datos de una solicitud
     * "ValorAcreditarRequest". Aquí hay un desglose de la función:
     * @param ValorAcreditar valoracreditar La función "actualizar" que proporcionó parece estar
     * actualizando una instancia del modelo "ValorAcreditar" basada en los datos de una solicitud
     * "ValorAcreditarRequest". Aquí hay un desglose del proceso:
     *
     * @return La función `update` está devolviendo una respuesta JSON con los datos `mensaje` y `modelo`.
     * La variable `mensaje` contiene un mensaje obtenido usando el método `Utils::obtenerMensaje` para la
     * acción 'almacenar' sobre la entidad. La variable `modelo` contiene el recurso `ValorAcreditar`
     * actualizado después de la operación de actualización. Si ocurre una excepción
     */
    public function update(ValorAcreditarRequest $request, ValorAcreditar $valoracreditar)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $valoracreditar = ValorAcreditar::findOrFail($request->id);
            $valoracreditar->update($datos);
            $modelo = new ValorAcreditarResource($valoracreditar->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()]);
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    /**
     * Esta función PHP elimina un registro específico y devuelve una respuesta JSON que contiene el
     * registro eliminado.
     *
     * @param Request request El parámetro `` en la función `destroy` es una instancia de la clase
     * `Illuminate\Http\Request`. Representa la solicitud HTTP que se realiza al servidor y contiene todos
     * los datos e información relacionados con la solicitud, como datos de entrada, encabezados, cookies,
     * etc. Este parámetro
     * @param ValorAcreditar valoracreditar El parámetro `valoracreditar` en la función `destroy` es una
     * instancia del modelo `ValorAcreditar`. Este parámetro se utiliza para identificar y eliminar un
     * registro específico de la base de datos. En esta instancia se llama al método `delete()` para
     * eliminar el registro de la base de datos.
     *
     * @return La función `destroy` elimina la instancia del modelo `ValorAcreditar` y luego devuelve una
     * respuesta JSON que contiene el objeto `valoracreditar` eliminado.
     */
    public function destroy(Request $request, ValorAcreditar $valoracreditar)
    {
        $valoracreditar->delete();
        return response()->json(compact('valoracreditar'));
    }
    /**
     * La función calcula el monto a acreditar a un usuario en función de su saldo actual y un umbral
     * mínimo.
     *
     * @param id La función `montoAcreditarUsuario` calcula el monto a acreditar a un usuario en función de
     * su DNI. Recupera el saldo actual y el umbral para el usuario de la base de datos, calcula la
     * diferencia entre el umbral y el saldo y luego determina el monto a acreditar redondeando la
     * diferencia al
     *
     * @return La función `montoAcreditarUsuario()` devuelve una respuesta JSON que contiene el valor
     * `monto_acreditar`.
     */
    public function montoAcreditarUsuario($id)
    {
        $saldo_actual = Saldo::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;
        $umbral_usuario = UmbralFondosRotativos::where('empleado_id', $id)->orderBy('id', 'desc')->first();
        $umbral_usuario = $umbral_usuario != null ? $umbral_usuario->valor_minimo : 0;
        $valorRecibir = $umbral_usuario - $saldo_actual;
        $monto_acreditar = abs(ceil($valorRecibir / 10) * 10);
        return response()->json(compact('monto_acreditar'));
    }
}
