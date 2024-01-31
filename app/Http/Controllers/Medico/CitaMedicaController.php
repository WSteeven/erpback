<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CitaMedicaRequest;
use App\Http\Resources\Medico\CitaMedicaResource;
use App\Models\Medico\CitaMedica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class CitaMedicaController extends Controller
{
    private $entidad = 'Cita Medica';

    public function __construct()
    {
        $this->middleware('can:puede.ver.citas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.citas_medicas')->only('store');
        $this->middleware('can:puede.editar.citas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.citas_medicas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = CitaMedica::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(CitaMedicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $citamedica = CitaMedica::create($datos);
            $modelo = new CitaMedicaResource($citamedica);
            $this->tabla_roles($citamedica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de citamedica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(CitaMedicaRequest $request, CitaMedica $citamedica)
    {
        $modelo = new CitaMedicaResource($citamedica);
        return response()->json(compact('modelo'));
    }


    public function update(CitaMedicaRequest $request, CitaMedica $citamedica)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $citamedica->update($datos);
            $modelo = new CitaMedicaResource($citamedica->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de citamedica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(CitaMedicaRequest $request, CitaMedica $citamedica)
    {
        try {
            DB::beginTransaction();
            $citamedica->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de citamedica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
