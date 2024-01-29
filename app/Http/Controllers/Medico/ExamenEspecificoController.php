<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenEspecificoRequest;
use App\Http\Resources\Medico\ExamenEspecificoResource;
use App\Models\Medico\ExamenEspecifico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ExamenEspecificoController extends Controller
{
    private $entidad = 'Factor de riesgo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.examenes_especificos')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes_especificos')->only('store');
        $this->middleware('can:puede.editar.examenes_especificos')->only('update');
        $this->middleware('can:puede.eliminar.examenes_especificos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ExamenEspecifico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ExamenEspecificoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $examen_especifico = ExamenEspecifico::create($datos);
            $modelo = new ExamenEspecificoResource($examen_especifico);
            $this->tabla_roles($examen_especifico);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen especifico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ExamenEspecificoRequest $request, ExamenEspecifico $examen_especifico)
    {
        $modelo = new ExamenEspecificoResource($examen_especifico);
        return response()->json(compact('modelo'));
    }


    public function update(ExamenEspecificoRequest $request, ExamenEspecifico $examen_especifico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $examen_especifico->update($datos);
            $modelo = new ExamenEspecificoResource($examen_especifico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen especifico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ExamenEspecificoRequest $request, ExamenEspecifico $examen_especifico)
    {
        try {
            DB::beginTransaction();
            $examen_especifico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen especifico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
