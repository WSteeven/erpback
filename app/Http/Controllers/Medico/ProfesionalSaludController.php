<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ProfesionalSaludRequest;
use App\Http\Resources\Medico\ProfesionalSaludResource;
use App\Models\Medico\ProfesionalSalud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ProfesionalSaludController extends Controller
{
    private $entidad = 'Profecional de la salud';

    public function __construct()
    {
        $this->middleware('can:puede.ver.profesionales_salud')->only('index', 'show');
        $this->middleware('can:puede.crear.profesionales_salud')->only('store');
        $this->middleware('can:puede.editar.profesionales_salud')->only('update');
        $this->middleware('can:puede.eliminar.profesionales_salud')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ProfesionalSalud::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ProfesionalSaludRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_evaluacion = ProfesionalSalud::create($datos);
            $modelo = new ProfesionalSaludResource($tipo_evaluacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de profecional de la salud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ProfesionalSalud $tipo_evaluacion)
    {
        $modelo = new ProfesionalSaludResource($tipo_evaluacion);
        return response()->json(compact('modelo'));
    }


    public function update(ProfesionalSaludRequest $request, ProfesionalSalud $tipo_evaluacion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_evaluacion->update($datos);
            $modelo = new ProfesionalSaludResource($tipo_evaluacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de profecional de la salud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ProfesionalSalud $tipo_evaluacion)
    {
        try {
            DB::beginTransaction();
            $tipo_evaluacion->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de profecional de la salud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
