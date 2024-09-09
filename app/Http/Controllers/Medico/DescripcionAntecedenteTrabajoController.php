<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DescripcionAntecedenteTrabajoRequest;
use App\Http\Resources\Medico\DescripcionAntecedenteTrabajoResource;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DescripcionAntecedenteTrabajoController extends Controller
{
    private $entidad = 'Descripcion de antecedente de trabajo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.descripciones_antecedentes_trabajos')->only('index', 'show');
        $this->middleware('can:puede.crear.descripciones_antecedentes_trabajos')->only('store');
        $this->middleware('can:puede.editar.descripciones_antecedentes_trabajos')->only('update');
        $this->middleware('can:puede.eliminar.descripciones_antecedentes_trabajos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = DescripcionAntecedenteTrabajo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(DescripcionAntecedenteTrabajoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $descripcion_antecedente_trabajo = DescripcionAntecedenteTrabajo::create($datos);
            $modelo = new DescripcionAntecedenteTrabajoResource($descripcion_antecedente_trabajo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de descripcion de antecedente de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(DescripcionAntecedenteTrabajoRequest $request, DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        $modelo = new DescripcionAntecedenteTrabajoResource($descripcion_antecedente_trabajo);
        return response()->json(compact('modelo'));
    }


    public function update(DescripcionAntecedenteTrabajoRequest $request, DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $descripcion_antecedente_trabajo->update($datos);
            $modelo = new DescripcionAntecedenteTrabajoResource($descripcion_antecedente_trabajo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de descripcion de antecedente de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(DescripcionAntecedenteTrabajoRequest $request, DescripcionAntecedenteTrabajo $descripcion_antecedente_trabajo)
    {
        try {
            DB::beginTransaction();
            $descripcion_antecedente_trabajo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de descripcion de antecedente de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
