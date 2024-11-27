<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecursosHumanos\ControlPersonal\AsistenciaResource;
use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Models\RecursosHumanos\ControlPersonal\Asistencia;
use Carbon\Carbon;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;

class AsistenciaController extends Controller
{
    public AsistenciaService $service;

    public function __construct()
    {
        $this->service = new AsistenciaService();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asistencias = Asistencia::filter()->get();
        $results = AsistenciaResource::collection(resource: $asistencias);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $datos = $this->consultarBiometrico();


            foreach ($datos as $evento) {
                $empleado = Empleado::whereRaw("CONCAT(nombres, ' ', apellidos) = ?", [$evento['employeeName']])->first();

                if ($empleado) {
                    // Definir el rango de almuerzo
                    $lunchStart = Carbon::createFromTime(12, 30);
                    $lunchEnd = Carbon::createFromTime(13, 30);

                    // Procesar eventos de almuerzo si no están definidos
                    $lunchOutTime = null;
                    $lunchInTime = null;

                    $horaEvento = Carbon::parse($evento['startTime']);

                    if ($horaEvento->between($lunchStart, $lunchEnd)) {
                        // Determinar si el evento es salida o entrada del almuerzo
                        if (is_null($evento['lunchOutTime'])) {
                            $lunchOutTime = $horaEvento;
                        } elseif (is_null($evento['lunchInTime'])) {
                            $lunchInTime = $horaEvento;
                        }
                    }

                    // Registrar o actualizar asistencia
                    Asistencia::updateOrCreate(
                        [
                            'empleado_id' => $empleado->id,
                            'hora_ingreso' => $evento['startTime'],
                        ],
                        [
                            'hora_salida' => $evento['endTime'] ?? null,
                            'hora_salida_almuerzo' => $lunchOutTime,
                            'hora_entrada_almuerzo' => $lunchInTime,
                        ]
                    );
                }
            }

            return response()->json(['message' => 'Asistencias sincronizadas correctamente.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al sincronizar asistencias.',
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
        $asistencia = Asistencia::with('empleado')->find($id);

        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada 1.'], 404);
        }

        return response()->json($asistencia);
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
        $asistencia = Asistencia::find($id);

        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada 2.'], 404);
        }

        $validatedData = $request->validate([
            'hora_ingreso' => 'sometimes|date',
            'hora_salida' => 'sometimes|date|after:hora_ingreso',
        ]);

        $asistencia->update($validatedData);

        return response()->json(['message' => 'Asistencia actualizada correctamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $asistencia = Asistencia::find($id);

        if (!$asistencia) {
            return response()->json(['message' => 'Asistencia no encontrada 3.'], 404);
        }

        $asistencia->delete();

        return response()->json(['message' => 'Asistencia eliminada correctamente.']);
    }

}
