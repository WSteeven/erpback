<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ActividadFisicaRequest;
use App\Http\Resources\Medico\ActividadFisicaResource;
use App\Models\Medico\ActividadFisica;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ActividadFisicaController extends Controller
{
    private $entidad = 'Estilo de vida';

    public function __construct()
    {
        $this->middleware('can:puede.ver.estilos_vida')->only('index', 'show');
        $this->middleware('can:puede.crear.estilos_vida')->only('store');
        $this->middleware('can:puede.editar.estilos_vida')->only('update');
        $this->middleware('can:puede.eliminar.estilos_vida')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ActividadFisica::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ActividadFisicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $estilo_vida = ActividadFisica::create($datos);
            $modelo = new ActividadFisicaResource($estilo_vida);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ActividadFisicaRequest $request, ActividadFisica $estilo_vida)
    {
        $modelo = new ActividadFisicaResource($estilo_vida);
        return response()->json(compact('modelo'));
    }


    public function update(ActividadFisicaRequest $request, ActividadFisica $estilo_vida)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $estilo_vida->update($datos);
            $modelo = new ActividadFisicaResource($estilo_vida->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ActividadFisicaRequest $request, ActividadFisica $estilo_vida)
    {
        try {
            DB::beginTransaction();
            $estilo_vida->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
