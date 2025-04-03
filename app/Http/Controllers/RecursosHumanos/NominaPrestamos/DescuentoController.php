<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\DescuentoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\DescuentoResource;
use App\Models\RecursosHumanos\NominaPrestamos\CuotaDescuento;
use App\Models\RecursosHumanos\NominaPrestamos\Descuento;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class DescuentoController extends Controller
{
    private string $entidad = "Descuento";

    public function __construct()
    {
        $this->middleware('can:puede.ver.descuentos')->only('index', 'show');
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
        Log::channel('testing')->info('Log', ['store', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            Log::channel('testing')->info('Log', ['datos validados', $datos]);

//            throw new Exception(Utils::metodoNoDesarrollado());
            $descuento = Descuento::create($datos);
            CuotaDescuento::actualizarCuotasDescuento($descuento, $datos['cuotas']);

            $modelo = new DescuentoResource($descuento);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable|Exception $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Guardar ' . $this->entidad);
        }
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
        } catch (Throwable|Exception $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex, 'Actualizar ' . $this->entidad);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy(/*$id*/)
    {
        throw ValidationException::withMessages([Utils::metodoNoDesarrollado()]);
    }
}
