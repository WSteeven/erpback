<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\AntecedenteTrabajoAnteriorRequest;
use App\Http\Resources\Medico\AntecedenteTrabajoAnteriorResource;
use App\Models\Medico\AntecedenteTrabajoAnterior;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AntecedenteTrabajoAnteriorController extends Controller
{
    private $entidad = 'Antecedente de trabajo anterior';

    public function __construct()
    {
        $this->middleware('can:puede.ver.antecedentes_trabajos_anteriores')->only('index', 'show');
        $this->middleware('can:puede.crear.antecedentes_trabajos_anteriores')->only('store');
        $this->middleware('can:puede.editar.antecedentes_trabajos_anteriores')->only('update');
        $this->middleware('can:puede.eliminar.antecedentes_trabajos_anteriores')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = AntecedenteTrabajoAnterior::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(AntecedenteTrabajoAnteriorRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $antecedente_trabajo_anterior = AntecedenteTrabajoAnterior::create($datos);
            $modelo = new AntecedenteTrabajoAnteriorResource($antecedente_trabajo_anterior);
            $this->tabla_roles($antecedente_trabajo_anterior);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente_trabajo_anterior' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(AntecedenteTrabajoAnteriorRequest $request, AntecedenteTrabajoAnterior $antecedente_trabajo_anterior)
    {
        $modelo = new AntecedenteTrabajoAnteriorResource($antecedente_trabajo_anterior);
        return response()->json(compact('modelo'));
    }


    public function update(AntecedenteTrabajoAnteriorRequest $request, AntecedenteTrabajoAnterior $antecedente_trabajo_anterior)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $antecedente_trabajo_anterior->update($datos);
            $modelo = new AntecedenteTrabajoAnteriorResource($antecedente_trabajo_anterior->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente_trabajo_anterior' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(AntecedenteTrabajoAnteriorRequest $request, AntecedenteTrabajoAnterior $antecedente_trabajo_anterior)
    {
        try {
            DB::beginTransaction();
            $antecedente_trabajo_anterior->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente_trabajo_anterior' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
