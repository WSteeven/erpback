<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaRequest;
use App\Http\Resources\TransferenciaResource;
use App\Models\Transferencia;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class TransferenciaController extends Controller
{
    private string $entidad = 'Transacción';

    public function __construct()
    {
        $this->middleware('can:puede.ver.transferencias')->only('index', 'show');
        $this->middleware('can:puede.crear.transferencias')->only('store');
        $this->middleware('can:puede.editar.transferencias')->only('update');
        $this->middleware('can:puede.eliminar.transferencias')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
//        $tipo = 'TRANSFERENCIA';
//        $estado = $request['estado'];
        $results = [];


        if (auth()->user()->hasRole([User::ROL_BODEGA, User::ROL_COORDINADOR_BODEGA, User::ROL_ADMINISTRADOR, User::ROL_BODEGA_TELCONET])) {
            $results = Transferencia::filter()->orderBy('id', 'desc')->get();
            /* if(auth()->user()->hasRole(User::ROL_ACTIVOS_FIJOS)){
                $results = Transferencia::filtrarPedidosActivosFijos($estado);
            } */
        }
        $results = TransferenciaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransferenciaRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(TransferenciaRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida en TRANSFERENCIA:', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            //Adaptación de foreing keys
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['sucursal_salida_id'] = $request->safe()->only(['sucursal_salida'])['sucursal_salida'];
            $datos['sucursal_destino_id'] = $request->safe()->only(['sucursal_destino'])['sucursal_destino'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];

            //Respuesta
            $transferencia = Transferencia::create($datos);
            $modelo = new TransferenciaResource($transferencia);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            //Guardamos el listado de productos en el detalle
            foreach ($request->listadoProductos as $listado) {
                $transferencia->items()->attach($listado['inventario_id'], ['cantidad' => $listado['cantidades']]);
            }
            //metodo para transferir productos de una bodega a otra.
            //Inventario::transferirProductos();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Transferencia $transferencia
     * @return JsonResponse
     */
    public function show(Transferencia $transferencia)
    {
        $modelo = new TransferenciaResource($transferencia);
        return response()->json(compact('modelo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param TransferenciaRequest $request
     * @param Transferencia $transferencia
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(TransferenciaRequest $request, Transferencia $transferencia)
    {
        Log::channel('testing')->info('Log', ['Request recibida en TRANSFERENCIA:', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            //Adaptación de foreing keys
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            $datos['sucursal_salida_id'] = $request->safe()->only(['sucursal_salida'])['sucursal_salida'];
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['sucursal_destino_id'] = $request->safe()->only(['sucursal_destino'])['sucursal_destino'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];

            //Borramos el listado anterior e ingresamos el nuevo
            //Guardamos el listado de productos en el detalle
            $transferencia->items()->detach();
            foreach ($request->listadoProductos as $listado) {
                $transferencia->items()->attach($listado['inventario_id'], ['cantidad' => $listado['cantidades']]);
            }
            //metodo para transferir productos de una bodega a otra.
            //Inventario::transferirProductos();
            //Respuesta
            $transferencia->update($datos);
            $modelo = new TransferenciaResource($transferencia->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Transferencia $transferencia
     * @return JsonResponse
     */
    public function destroy(Transferencia $transferencia)
    {
        $transferencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Consultar datos sin el método show
     */
    public function showPreview(Transferencia $transferencia)
    {
        $modelo = new TransferenciaResource($transferencia);

        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function anular(Transferencia $transferencia)
    {
        try {
            DB::beginTransaction();
            if ($transferencia->estado === Transferencia::ANULADO)
                throw new Exception("Esta transferencia ya ha sido anulada anteriormente");
            $transferencia->update(['estado' => Transferencia::ANULADO]);
            $mensaje = 'Transferencia anulada correctamente';
            $modelo = new TransferenciaResource($transferencia->refresh());
            DB::commit();
        } catch (Exception $e) {
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }
}
