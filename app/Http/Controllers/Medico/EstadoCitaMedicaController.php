<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EstadoCitaMedicaRequest;
use App\Http\Resources\Medico\EstadoCitaMedicaResource;
use App\Models\Medico\EstadoCitaMedica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EstadoCitaMedicaController extends Controller
{
    private $entidad = 'Examen Estado de cita medica';

    public function __construct()
    {
        $this->middleware('can:puede.ver.estados_citas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.estados_citas_medicas')->only('store');
        $this->middleware('can:puede.editar.estados_citas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.estados_citas_medicas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = EstadoCitaMedica::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(EstadoCitaMedicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha_aptitud = EstadoCitaMedica::create($datos);
            $modelo = new EstadoCitaMedicaResource($ficha_aptitud);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estado de cita medica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EstadoCitaMedica $ficha_aptitud)
    {
        $modelo = new EstadoCitaMedicaResource($ficha_aptitud);
        return response()->json(compact('modelo'));
    }


    public function update(EstadoCitaMedicaRequest $request, EstadoCitaMedica $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $ficha_aptitud->update($datos);
            $modelo = new EstadoCitaMedicaResource($ficha_aptitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estado de cita medica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EstadoCitaMedica $ficha_aptitud)
    {
        try {
            DB::beginTransaction();
            $ficha_aptitud->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estado de cita medica' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
