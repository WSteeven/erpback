<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DiagnosticoRequest;
use App\Http\Resources\Medico\DiagnosticoResource;
use App\Models\Medico\Diagnostico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DiagnosticoController extends Controller
{
    private $entidad = 'Diagnostico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.diagnosticos')->only('index', 'show');
        $this->middleware('can:puede.crear.diagnosticos')->only('store');
        $this->middleware('can:puede.editar.diagnosticos')->only('update');
        $this->middleware('can:puede.eliminar.diagnosticos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Diagnostico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(DiagnosticoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $diagnostico = Diagnostico::create($datos);
            $modelo = new DiagnosticoResource($diagnostico);
            $this->tabla_roles($diagnostico);
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

    public function show(DiagnosticoRequest $request, Diagnostico $diagnostico)
    {
        $modelo = new DiagnosticoResource($diagnostico);
        return response()->json(compact('modelo'));
    }


    public function update(DiagnosticoRequest $request, Diagnostico $diagnostico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $diagnostico->update($datos);
            $modelo = new DiagnosticoResource($diagnostico->refresh());
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

    public function destroy(DiagnosticoRequest $request, Diagnostico $diagnostico)
    {
        try {
            DB::beginTransaction();
            $diagnostico->delete();
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
