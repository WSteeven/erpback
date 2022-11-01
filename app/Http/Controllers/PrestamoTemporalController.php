<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrestamoTemporalRequest;
use App\Http\Resources\PrestamoTemporalResource;
use App\Models\PrestamoTemporal;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PrestamoTemporalController extends Controller
{
    private $entidad = 'Prestamo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.prestamos')->only('index', 'show');
        $this->middleware('can:puede.crear.prestamos')->only('store');
        $this->middleware('can:puede.editar.prestamos')->only('update');
        $this->middleware('can:puede.eliminar.prestamos')->only('destroy');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = PrestamoTemporalResource::collection(PrestamoTemporal::all());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(PrestamoTemporalRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida:', $request->all()]);

        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            Log::channel('testing')->info('Log', ['Pasó la validacion, solo los bodegueros pueden hacer prestamos']);
            try {
                $datos = $request->validated();
                Log::channel('testing')->info('Log', ['Datos validados', $datos]);
                DB::beginTransaction();
                //Adaptación de foreign keys
                $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
                $datos['per_entrega_id'] = $request->safe()->only(['per_entrega'])['per_entrega'];

                //Respuesta
                $prestamo = PrestamoTemporal::create($datos);
                $modelo = new PrestamoTemporalResource($prestamo);
                $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

                foreach ($request->listadoProductos as $listado) {
                    Log::channel('testing')->info('Log', ['Listado recibido en el foreach:', $listado]);
                    // Log::channel('testing')->info('Log', ['Producto y cantidad inicial:', $listado['descripcion'], $listado['cantidades']]);
                    $prestamo->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidad']]);
                }
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage()]);
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
            }
        }
    }


    /**
     * Consultar
     */
    public function show(PrestamoTemporal $prestamo)
    {
        $modelo = new PrestamoTemporalResource($prestamo);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(PrestamoTemporalRequest $request, PrestamoTemporal  $prestamo)
    {
        Log::channel('testing')->info('Log', ['Solicitante es:', $prestamo->solicitante->id]);
        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $prestamo->update(($request->validated()))
        }
        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            $transaccion->update($request->validated());
            $modelo = new TransaccionBodegaResource($transaccion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            return response()->json(compact('mensaje', 'modelo'));
        }
        $message = 'No tienes autorización para modificar esta solicitud';
        $errors = ['message' => $message];
        return response()->json(['errors' => $errors], 422);
    }

    /**
     * Eliminar
     */
    public function destroy(TransaccionBodega $transaccion)
    {
        $transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
