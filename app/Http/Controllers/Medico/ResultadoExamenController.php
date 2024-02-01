<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ResultadoExamenRequest;
use App\Http\Resources\Medico\ResultadoExamenResource;
use App\Models\Medico\ResultadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ResultadoExamenController extends Controller
{
    private $entidad = 'Resultados de examenes';

    public function __construct()
    {
        $this->middleware('can:puede.ver.resultados_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.resultados_examenes')->only('store');
        $this->middleware('can:puede.editar.resultados_examenes')->only('update');
        $this->middleware('can:puede.eliminar.resultados_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ResultadoExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ResultadoExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $resultado_examen = ResultadoExamen::create($datos);
            $modelo = new ResultadoExamenResource($resultado_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ResultadoExamenRequest $request, ResultadoExamen $resultado_examen)
    {
        $modelo = new ResultadoExamenResource($resultado_examen);
        return response()->json(compact('modelo'));
    }


    public function update(ResultadoExamenRequest $request, ResultadoExamen $resultado_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $resultado_examen->update($datos);
            $modelo = new ResultadoExamenResource($resultado_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ResultadoExamenRequest $request, ResultadoExamen $resultado_examen)
    {
        try {
            DB::beginTransaction();
            $resultado_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de resultado de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }}
