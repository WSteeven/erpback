<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\ServicioRequest;
use App\Http\Resources\Vehiculos\ServicioResource;
use App\Models\Vehiculos\Servicio;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class ServicioController extends Controller
{
    private string $entidad = 'Servicio';
    public function __construct()
    {
        $this->middleware('can:puede.ver.servicios_mantenimientos')->only('index', 'show');
        $this->middleware('can:puede.crear.servicios_mantenimientos')->only('store');
        $this->middleware('can:puede.editar.servicios_mantenimientos')->only('update');
        $this->middleware('can:puede.eliminar.servicios_mantenimientos')->only('destroy');
    }


    public function index()
    {
        if (request()->search)
            $results = Servicio::search(request()->search)->orderBy('nombre')->get();
        else
            // $results = Servicio::ignoreRequest(['search'])->filter()->orderBy('nombre', 'asc')->get();
            // Cambio realizado para que a Pedro Aguilar le aparezcan todos los mantenimientos.
            $results = Servicio::orderBy('nombre')->get();
        $results = ServicioResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(ServicioRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $servicio = Servicio::create($datos);
            $modelo = new ServicioResource($servicio);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al registrar el servicio: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
        }
    }


    public function show(Servicio $servicio)
    {
        $modelo = new ServicioResource($servicio);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(ServicioRequest $request, Servicio $servicio)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $servicio->update($datos);
            $modelo = new ServicioResource($servicio);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el servicio: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
        }
    }

    public function desactivar(Servicio $servicio)
    {
        $servicio->estado = !$servicio->estado;
        $servicio->save();

        $modelo = new ServicioResource($servicio->refresh());
        $mensaje = 'Servicio actualizado correctamente';
        return response()->json(compact('modelo', 'mensaje'));
    }
}
