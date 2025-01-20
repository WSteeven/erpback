<?php

namespace App\Http\Controllers\RecursosHumanos\ControlPersonal;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecursosHumanos\ControlPersonal\AsistenciaResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\ControlPersonal\Asistencia;
use App\Models\ControlPersonal\Marcacion;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RecursosHumanos\ControlPersonal\AsistenciaService;
use Src\Shared\Utils;

class AsistenciaController extends Controller
{
    public AsistenciaService $service;
    private string $entidad = 'Asistencia';

    public function __construct()
    {
        $this->service = new AsistenciaService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $asistencias = Asistencia::filter()->orderBy('fecha', 'desc')->get();
        //$results = HorarioLaboral::filter()->orderBy('fecha', 'desc')->get();
        $results = AsistenciaResource::collection(resource: $asistencias);

        return response()->json(compact('results'));
    }

    // Method POST
    public function store(Request $request)
    {

        $modelo = Asistencia::create($request->validated());

        return response()->json(compact('modelo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
/*     public function sincronizarAsistencias()
    {
        try {
            $datos = $this->service->obtenerRegistrosDiarios();
            // $horario_laborar = HorarioLaboral::first();

            // Validar que 'InfoList' exista en la respuesta
            //            if (!isset($datos['AcsEvent']['InfoList'])) {
            //                throw new Exception("La clave 'InfoList' no está presente en los datos obtenidos.");
            //            }
            //
            //            $eventos = $datos['AcsEvent']['InfoList'];
            $eventos = $datos;

            // Filtrar eventos con 'minor' igual a 75 o 38
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['minor']) && in_array($evento['minor'], [75, 38]);
            });

            // Validar que los eventos tienen las claves 'name' y 'time'
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['name']) && isset($evento['time']);
            });

            // Ordenar eventos por hora para procesarlos en orden cronológico desdel el mas reciente al mas antiguo
            usort($eventos, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

            // Agrupar eventos por empleado y fecha
            $eventosAgrupados = [];
            foreach ($eventos as $evento) {
                $fechaEvento = Carbon::parse($evento['time'])->format('Y-m-d');
                $eventosAgrupados[$evento['name']][$fechaEvento][] = $evento;
            }

            // Horarios esperados
            $horarios = [
                'ingreso' => ['start' => '00:00:00', 'end' => '11:59:59'],
                'salida_almuerzo' => ['start' => '12:00:00', 'end' => '13:00:59'],
                'entrada_almuerzo' => ['start' => '13:01:00', 'end' => '14:00:00'],
                'salida' => ['start' => '16:30:00', 'end' => '23:59:59'],
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
                            $asistencia["hora_$tipo"] = Carbon::parse($eventoSeleccionado['time'])->format('H:i:s');

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
        } catch (GuzzleException $e) {
            Log::channel('testing')->info('Log', ['GuzzleException en sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw Utils::obtenerMensajeErrorLanzable($e);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['Exception en sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw ValidationException::withMessages([
                'message' => 'Error al sincronizar asistencias.',
                'details' => $e->getMessage(),
            ]);
        }
    } */



    public function sincronizarAsistencias()
    {
        try {
            // Obtener registros de la tabla 'marcaciones'
            $registros = Marcacion::all();

            // Definir horarios esperados
            $horarios = [
                'hora_ingreso' => ['start' => '00:00:00', 'end' => '11:59:59'],
                'hora_salida_almuerzo' => ['start' => '12:00:00', 'end' => '13:00:59'],
                'hora_entrada_almuerzo' => ['start' => '13:01:00', 'end' => '14:00:00'],
                'hora_salida' => ['start' => '16:30:00', 'end' => '23:59:59'],
            ];

            foreach ($registros as $registro) {
                $empleado = Empleado::find($registro->empleado_id);
                if (!$empleado) {
                    continue;
                }

                $fecha = Carbon::parse($registro->fecha)->format('Y-m-d');

                // Asegurar que el campo 'marcaciones' es un string antes de decodificar
                $marcacionesJson = is_string($registro->marcaciones) ? $registro->marcaciones : json_encode($registro->marcaciones);
                $marcaciones = json_decode($marcacionesJson, true);

                if (!is_array($marcaciones) || empty($marcaciones)) {
                    continue;
                }

                // Inicializar asistencia
                $asistencia = [
                    'hora_ingreso' => null,
                    'hora_salida_almuerzo' => null,
                    'hora_entrada_almuerzo' => null,
                    'hora_salida' => null,
                ];

                foreach ($horarios as $tipo => $rango) {
                    $eventoSeleccionado = null;

                    foreach ($marcaciones as $hora) {
                        if ($hora >= $rango['start'] && $hora <= $rango['end']) {
                            if (!$eventoSeleccionado || $hora < $eventoSeleccionado) {
                                $eventoSeleccionado = $hora;
                            }
                        }
                    }

                    if ($eventoSeleccionado) {
                        $asistencia[$tipo] = $eventoSeleccionado;
                    }
                }

                // Guardar asistencia si hay datos
                if (array_filter($asistencia)) {
                    Asistencia::updateOrCreate(
                        ['empleado_id' => $empleado->id, 'fecha' => $fecha],
                        $asistencia
                    );
                }
            }

            return response()->json(['message' => 'Asistencias sincronizadas correctamente.']);
        } catch (Exception $e) {
            Log::error('Error en sincronizarAsistencias: ' . $e->getMessage());
            return response()->json(['message' => 'Error al sincronizar asistencias', 'details' => $e->getMessage()], 500);
        }
    }




    /**
     * Display the specified resource.
     *
     * @param Asistencia $asistencia
     * @return JsonResponse
     */
    public function show(Asistencia $asistencia)
    {
        $modelo = new AsistenciaResource($asistencia);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Asistencia $asistencia
     * @return JsonResponse
     */
    public function update(Request $request, Asistencia $asistencia)
    {
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
     * @param Asistencia $asistencia
     * @return JsonResponse
     */
    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
