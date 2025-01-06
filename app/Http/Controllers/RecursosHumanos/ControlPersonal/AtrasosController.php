<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\ControlPersonal\AtrasosRequest;
use App\Http\Resources\RecursosHumanos\ControlPersonal\AtrasosResource;
use App\Models\RecursosHumanos\ControlPersonal\Asistencia;
use App\Models\RecursosHumanos\ControlPersonal\Atrasos;
use App\Models\Empleado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AtrasosController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:ver.atrasos')->only('index', 'show');
        $this->middleware('can:editar.atrasos')->only('update');
        $this->middleware('can:eliminar.atrasos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $atrasos = Atrasos::filter()->orderBy('fecha_atraso', 'asc')->get();
        $results = AtrasosResource::collection($atrasos);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\RecursosHumanos\ControlPersonal\AtrasosRequest $request
     * @return \Illuminate\Http\Response
     */public function store(AtrasosRequest $request)
{
    DB::beginTransaction();

    try {
        // Obtener todas las asistencias
        $asistencias = Asistencia::all(); // Consultar todos los registros de la tabla

        foreach ($asistencias as $asistencia) {
            // Suponiendo que la hora esperada está directamente en la tabla de asistencias
            $horaEsperada = Carbon::parse($asistencia->hora_entrada); // Hora de entrada esperada
            $horaReal = Carbon::parse($asistencia->hora_ingreso); // Hora de ingreso real

            // Calcular los minutos y segundos de atraso
            $minutosAtraso = max(0, $horaReal->diffInMinutes($horaEsperada, false));
            $segundosAtraso = max(0, $horaReal->diffInSeconds($horaEsperada, false) % 60);

            // Registrar atraso sólo si hay un atraso real
            if ($minutosAtraso > 0 || $segundosAtraso > 0) {
                Atrasos::updateOrCreate(
                    [
                        'empleado_id' => $asistencia->empleado_id, // Usando directamente empleado_id de la asistencia
                        'asistencia_id' => $asistencia->id,       // ID de la asistencia
                        'fecha_atraso' => $asistencia->fecha,     // Suponiendo que `fecha` está en la tabla asistencia
                    ],
                    [
                        'minutos_atraso' => $minutosAtraso,
                        'segundos_atraso' => $segundosAtraso,
                        'requiere_justificacion' => false, // Valor por defecto, ajusta según tus necesidades
                        'justificacion_atraso' => null,    // No hay justificación por defecto
                    ]
                );
            }
        }

        DB::commit();
        return response()->json(['message' => 'Atrasos registrados correctamente.']);
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error registrando atrasos', ['exception' => $e]);
        return response()->json([
            'message' => 'Error al registrar atrasos.',
            'details' => $e->getMessage(),
        ], 500);
    }
}




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $atraso = Atrasos::with('asistencia_id')->find($id);

        if (!$atraso) {
            return response()->json(['message' => 'Atraso no encontrado 1.'], 404);
        }

        return response()->json($atraso);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $atraso = Atrasos::find($id);

        if (!$atraso) {
            return response()->json(['message' => 'Atraso no encontrado 2.'], 404);
        }

        $validatedData = $request->validate([
            'minutos_atraso' => 'sometimes|integer|min:0',
            'segundos_atraso' => 'sometimes|integer|min:0',
            'requiere_justificacion' => 'sometimes|boolean',
            'justificacion_atraso' => 'sometimes|string|max:500',
        ]);

        $atraso->update($validatedData);

        return response()->json(['message' => 'Atraso actualizado correctamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $atraso = Atrasos::find($id);

        if (!$atraso) {
            return response()->json(['message' => 'Atraso no encontrado 3.'], 404);
        }

        $atraso->delete();

        return response()->json(['message' => 'Atraso eliminado correctamente.']);
    }
}
