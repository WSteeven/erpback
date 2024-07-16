<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\LaboratorioClinicoRequest;
use App\Http\Resources\Medico\LaboratorioClinicoResource;
use App\Models\Medico\LaboratorioClinico;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class LaboratorioClinicoController extends Controller
{
    private $entidad = 'Laboratorio clinico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.laboratorios_clinicos')->only('index', 'show');
        $this->middleware('can:puede.crear.laboratorios_clinicos')->only('store');
        $this->middleware('can:puede.editar.laboratorios_clinicos')->only('update');
        $this->middleware('can:puede.eliminar.laboratorios_clinicos')->only('destroy');
    }

    public function index()
    {
        // $results = $this->examenService->listar();
        $results = LaboratorioClinico::ignoreRequest(['campos'])->filter()->get();
        $results = LaboratorioClinicoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(LaboratorioClinicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $examen = LaboratorioClinico::create($datos);
            $modelo = new LaboratorioClinicoResource($examen);
            // $this->tabla_roles($examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function show(LaboratorioClinico $laboratorio_clinico)
    {
        $modelo = new LaboratorioClinicoResource($laboratorio_clinico);
        return response()->json(compact('modelo'));
    }

    public function update(LaboratorioClinicoRequest $request, LaboratorioClinico $laboratorio_clinico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $laboratorio_clinico->update($datos);
            $modelo = new LaboratorioClinicoResource($laboratorio_clinico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de ' . $this->entidad . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(LaboratorioClinico $laboratorio_clinico)
    {
        try {
            DB::beginTransaction();
            $laboratorio_clinico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de ' . $this->entidad . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
