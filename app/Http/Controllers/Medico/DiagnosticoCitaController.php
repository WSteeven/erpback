<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DiagnosticoCitaRequest;
use App\Http\Resources\Medico\DiagnosticoCitaResource;
use App\Models\Medico\DiagnosticoCita;
use App\Models\Medico\DiagnosticoCitaMedica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class DiagnosticoCitaController extends Controller
{
    private $entidad = 'Diagnostico cita';

    public function __construct()
    {
        $this->middleware('can:puede.ver.diagnosticos_citas_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.diagnosticos_citas_medicas')->only('store');
        $this->middleware('can:puede.editar.diagnosticos_citas_medicas')->only('update');
        $this->middleware('can:puede.eliminar.diagnosticos_citas_medicas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = DiagnosticoCitaMedica::ignoreRequest(['campos'])->filter()->get();
        $results = DiagnosticoCitaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiagnosticoCitaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $diagnosticocita = DiagnosticoCitaMedica::create($datos);
            $modelo = new DiagnosticoCitaResource($diagnosticocita);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de diagnosticocita' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  DiagnosticoCita  $diagnostico_cita
     * @return \Illuminate\Http\Response
     */
    public function show(DiagnosticoCitaMedica $diagnostico_cita)
    {
        $modelo = new DiagnosticoCitaResource($diagnostico_cita);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  DiagnosticoCita  $diagnostico_cita
     * @return \Illuminate\Http\Response
     */
    public function update(DiagnosticoCitaRequest $request, $diagnostico_cita)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $diagnostico_cita->update($datos);
            $modelo = new DiagnosticoCitaResource($diagnostico_cita->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de diagnosticocita' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DiagnosticoCita  $diagnostico_cita
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiagnosticoCitaMedica $diagnostico_cita)
    {
        try {
            DB::beginTransaction();
            $diagnostico_cita->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de diagnosticocita' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
