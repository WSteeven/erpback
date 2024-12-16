<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\ControlPersonal\AsistenciaRequest;
use App\Http\Resources\RecursosHumanos\ControlPersonal\AsistenciaResource;
use App\Models\Empleado;
use Illuminate\Http\Request;
use App\Models\RecursosHumanos\ControlPersonal\Asistencia;
use App\Models\RecursosHumanos\ControlPersonal\HorarioLaboral;
use Carbon\Carbon;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;

use Illuminate\Support\Facades\Log;

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
        $asistencias = Asistencia::filter()->orderBy('fecha', 'desc')->get();
        //$results = HorarioLaboral::filter()->orderBy('fecha', 'desc')->get();
        $results = AsistenciaResource::collection(resource: $asistencias);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AsistenciaRequest $request)
    {
        try {
            $datos = $this->service->obtenerRegistrosDiarios();
            $horario_laborar = HorarioLaboral::first();

            // Validar que 'InfoList' exista en la respuesta
            if (!isset($datos['AcsEvent']['InfoList'])) {
                throw new \Exception("La clave 'InfoList' no está presente en los datos obtenidos.");
            }

            $eventos = $datos['AcsEvent']['InfoList'];

            // Filtrar eventos con 'minor' igual a 75 o 38
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['minor']) && in_array($evento['minor'], [75, 38]);
            });

            // Validar que los eventos tienen la clave 'name'
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['name']);
            });

            // Ordenar eventos por hora para procesarlos en orden cronológico
            usort($eventos, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

            // Agrupar eventos por empleado y fecha
            $eventosAgrupados = [];
            foreach ($eventos as $evento) {
                $fechaEvento = Carbon::parse($evento['time'])->format('Y-m-d');
                $eventosAgrupados[$evento['name']][$fechaEvento][] = $evento;
            }

            // Horarios esperados
            $horarios = [
                'ingreso' => ['start' => '07:00:00', 'end' => '12:29:59'],
                'salida_almuerzo' => ['start' => '12:30:00', 'end' => '13:00:00'],
                'entrada_almuerzo' => ['start' => '13:01:00', 'end' => '14:00:00'],
                'salida' => ['start' => '16:30:00', 'end' => '17:30:00'],
            ];

            // Procesar eventos agrupados
            foreach ($eventosAgrupados as $nombreEmpleado => $fechas) {
                $empleado = Empleado::whereRaw("CONCAT(nombres, ' ', apellidos) = ?", [$nombreEmpleado])->first();

                if (!$empleado) {
                    continue; // Saltar si no se encuentra el empleado
                }

                foreach ($fechas as $fecha => $eventosDelDia) {
                    $asistencia = [
                        'hora_ingreso' => null,
                        'hora_salida_almuerzo' => null,
                        'hora_entrada_almuerzo' => null,
                        'hora_salida' => null,
                    ];

                    foreach ($horarios as $tipo => $rango) {
                        $eventoSeleccionado = null;

                        foreach ($eventosDelDia as $evento) {
                            // Convertir la hora del evento a la zona horaria local
                            $horaEvento = Carbon::parse($evento['time'])->format('H:i:s');

                            if ($horaEvento >= $rango['start'] && $horaEvento <= $rango['end']) {
                                // Seleccionar el evento más temprano en el rango
                                if (!$eventoSeleccionado || $horaEvento < Carbon::parse($eventoSeleccionado['time'])->format('H:i:s')) {
                                    $eventoSeleccionado = $evento;
                                }
                            }
                        }

                        if ($eventoSeleccionado) {
                            $asistencia["hora_{$tipo}"] = Carbon::parse($eventoSeleccionado['time'])->format('H:i:s');

                            // Eliminar el evento seleccionado para evitar duplicados
                            $eventosDelDia = array_filter($eventosDelDia, function ($e) use ($eventoSeleccionado) {
                                $horaEventoSeleccionado = Carbon::parse($eventoSeleccionado['time'])->format('H:i:s');
                                $horaEvento = Carbon::parse($e['time'])->format('H:i:s');
                                return $horaEvento !== $horaEventoSeleccionado;
                            });
                        }
                    }

                    // Verificar que al menos un horario esté presente antes de guardar
                    if (array_filter($asistencia)) {
                        Asistencia::updateOrCreate(
                            [
                                'empleado_id' => $empleado->id,
                                'fecha' => $fecha,
                            ],
                            $asistencia
                        );
                    }
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
        $asistencia = Asistencia::with('empleado_id')->find($id);

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
