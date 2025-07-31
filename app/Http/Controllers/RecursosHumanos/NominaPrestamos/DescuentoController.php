<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\DescuentoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\DescuentoResource;
use App\Models\RecursosHumanos\NominaPrestamos\CuotaDescuento;
use App\Models\RecursosHumanos\NominaPrestamos\Descuento;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class DescuentoController extends Controller
{
    private string $entidad = "Descuento";

    public function __construct()
    {
        $this->middleware('can:puede.ver.descuentos')->only('index', 'show');
        $this->middleware('can:puede.crear.descuentos')->only('store');
        $this->middleware('can:puede.editar.descuentos')->only('update');
        $this->middleware('can:puede.eliminar.descuentos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Descuento::filter()->get();
        $results = DescuentoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DescuentoRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(DescuentoRequest $request)
    {
//        Log::channel('testing')->info('Log', ['store', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $descuento = Descuento::create($datos);
            CuotaDescuento::actualizarCuotasDescuento($descuento, $datos['cuotas']);

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Guardar ' . $this->entidad);
        }
        $modelo = new DescuentoResource($descuento);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Descuento $descuento
     * @return JsonResponse
     */
    public function show(Descuento $descuento)
    {
        $modelo = new DescuentoResource($descuento);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DescuentoRequest $request
     * @param Descuento $descuento
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(DescuentoRequest $request, Descuento $descuento)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $descuento->update($datos);
            CuotaDescuento::actualizarCuotasDescuento($descuento, $datos['cuotas']);
            $modelo = new DescuentoResource($descuento);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Actualizar ' . $this->entidad);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function destroy(Descuento $descuento)
    {
        try {
            // si ya ha sido pagado, no se puede eliminar
            if ($descuento->pagado) throw new Exception("No se puede eliminar un descuento que ya ha sido pagado");
            // si tiene al menos 1 cuota pagada, tampoco se puede eliminar
            if ($descuento->cuotas()->where('pagada', true)->count() > 0) throw new Exception("No se puede eliminar este descuento porque ya tiene cuotas que han sido pagadas, por favor regulariza de otra manera");

            //Se procede con normalidad
            // borramos las cuotas registradas en egreso_rol_pago, luego las cuotas y luego el descuento como tal
            foreach ($descuento->cuotas()->get() as $cuota) {
                $cuota->egreso_rol_pago()->delete();
            }
            $descuento->cuotas()->delete();
            $descuento->delete();
        } catch (Throwable $ex) {
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
//        throw ValidationException::withMessages([Utils::metodoNoDesarrollado()]);
    }

    /**
     * Calcula la cantidad de cuotas para devolver al front un array con los valores necesarios
     * @throws Exception
     */
    public function calcularCantidadCuotas(Request $request)
    {
        $mes_inicia_cobro = Carbon::createFromFormat('Y-m', $request->mes_inicia_cobro)->endOfMonth();
        // si el mes de inicio de cobro es menor a la fecha de descuento, se lanza un error
        if ($mes_inicia_cobro->lt(Carbon::parse($request->fecha_descuento))) throw new Exception('La fecha de descuento debe ser menor al mes de inicio del cobro');

        $cuotas = $this->obtenerCuotasDescuento($mes_inicia_cobro, $request->valor, $request->cantidad_cuotas);


        return response()->json(compact('cuotas'));
    }

    /**
     * Realiza el calculo de cuotas a pagar junto con el valor de cada cuota y el mes que debe pagarse,
     * teniendo en cuenta como primer mes el mes_inicia_cobro y el valor para dividir en cuotas iguales
     * @param Carbon $mes_inicia_cobro
     * @param float $valor
     * @param int|null $cantidad_cuotas
     * @return array
     */
    private function obtenerCuotasDescuento(Carbon $mes_inicia_cobro, float $valor = 0, ?int $cantidad_cuotas = 1)
    {
        if ($valor <= 0) return [];
        $cuotas = [];

        //Redondear al centavo base
        $valor_cuota_base = round($valor / $cantidad_cuotas, 2);

        // Calcular el total con cuota base
        $total_cuotas_base = $valor_cuota_base * $cantidad_cuotas;

        // Determinar diferencia a ajustar (positiva o negativa)
        $diferencia = round($valor - $total_cuotas_base, 2);

        for ($i = 1; $i <= $cantidad_cuotas; $i++) {
            $mes = $mes_inicia_cobro->copy()->addMonthsNoOverflow($i - 1);

            // Ajustamos la primera cuota con la diferencia (si existe)
            $ajuste = 0;
            if ($diferencia !== 0.0) {
                $ajuste = $diferencia;
                $diferencia = 0.0; // solo se ajusta una vez
            }

            $cuotas[] = [
                'id' => $i,
                'num_cuota' => $i,
                'mes_vencimiento' => $mes->format('Y-m'),
                'valor_cuota' => round($valor_cuota_base + $ajuste, 2),
                'pagada' => false,
                'comentario' => null,
            ];
        }

        return $cuotas;

    }
}
