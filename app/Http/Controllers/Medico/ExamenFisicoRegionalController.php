<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenFisicoRegionalRequest;
use App\Http\Resources\Medico\ExamenFisicoRegionalResource;
use App\Models\Medico\ExamenFisicoRegional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ExamenFisicoRegionalController extends Controller
{
    private $entidad = 'Examen fisico regional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.examenes_fisicos_regionales')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes_fisicos_regionales')->only('store');
        $this->middleware('can:puede.editar.examenes_fisicos_regionales')->only('update');
        $this->middleware('can:puede.eliminar.examenes_fisicos_regionales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ExamenFisicoRegional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ExamenFisicoRegionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $examen_fisico_regional = ExamenFisicoRegional::create($datos);
            $modelo = new ExamenFisicoRegionalResource($examen_fisico_regional);
            $this->tabla_roles($examen_fisico_regional);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen fisico regional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ExamenFisicoRegionalRequest $request, ExamenFisicoRegional $examen_fisico_regional)
    {
        $modelo = new ExamenFisicoRegionalResource($examen_fisico_regional);
        return response()->json(compact('modelo'));
    }


    public function update(ExamenFisicoRegionalRequest $request, ExamenFisicoRegional $examen_fisico_regional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $examen_fisico_regional->update($datos);
            $modelo = new ExamenFisicoRegionalResource($examen_fisico_regional->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen fisico regional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ExamenFisicoRegionalRequest $request, ExamenFisicoRegional $examen_fisico_regional)
    {
        try {
            DB::beginTransaction();
            $examen_fisico_regional->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen fisico regional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
