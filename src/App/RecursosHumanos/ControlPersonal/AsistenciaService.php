<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use App\Models\ControlPersonal\Marcacion;
use App\Models\Empleado;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * TODO: Codigos de minor
 * TODO: 0x26 => 38 => Fingerprint Authentication Completed
 * TODO: 0x4b => 75 => Face Authentication Completed
 **
 */
class AsistenciaService
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('HIKVISION_BASE_URL'),
            'timeout' => 10.0,
            'auth' => [env('HIKVISION_USER'), env('HIKVISION_PASSWORD'), 'digest'],
        ]);
    }

    /**
     * INFO: Metodo con el que se esta trabajando, consultando desde asistenciaController
     * Este metodo esta implementado en FetchHikVisionRecords
     * Obtiene todos los eventos del mes del biometrico.
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function obtenerRegistrosDiarios()
    {
        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->endOfMonth()->toIso8601String();
        /* $startTime = Carbon::now()->subMonth()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->subMonth()->endOfMonth()->toIso8601String(); */
        $maxResults = 30; // Ajustar al límite del dispositivo
        $searchResultPosition = 0;
        $eventosTotales = [];

        try {
            do {
                // Crear las condiciones de búsqueda
                $ascEventCond = [
                    "searchID" => "1",
                    "searchResultPosition" => $searchResultPosition,
                    "maxResults" => $maxResults,
                    "major" => 5,
                    "minor" => 0,
                    "startTime" => $startTime,
                    "endTime" => $endTime,
                    "picEnable" => false,
                    "eventAttribute" => "attendance",
                    "currentVerifyMode" => "cardOrFaceOrFp",
                    "timeReverseOrder" => true,
                ];

                // Realizar la consulta
                $response = $this->client->post($endpoint, [
                    "json" => ["AcsEventCond" => $ascEventCond],
                ]);

                $data = json_decode($response->getBody(), true);

                // Validar que la respuesta contiene eventos
                if (isset($data['AcsEvent']['InfoList']) && is_array($data['AcsEvent']['InfoList'])) {
                    $eventosTotales = array_merge($eventosTotales, $data['AcsEvent']['InfoList']);
                    $searchResultPosition += count($data['AcsEvent']['InfoList']);
                } else {
                    break; // Salir si no hay más eventos
                }
            } while (count($data['AcsEvent']['InfoList']) === $maxResults);

//            Log::channel('testing')->info('Log', ['obtenerRegistrosDiarios-> eventos obtenidos', $eventosTotales]);

            return $eventosTotales;
//            return ['AcsEvent' => ['InfoList' => $eventosTotales]];
        } catch (Exception $e) {
            // Manejar errores en caso de falla
            Log::channel('testing')->info('Log', ['Exception en obtenerRegistrosDiarios:', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function sincronizarAsistencias()
    {
        try {
            $datos = $this->obtenerRegistrosDiarios();

            //De los eventos recibidos filtramos para obtener solo los eventos con 'minor' igual a 75 o 38
            $eventos = array_filter($datos, function ($evento) {
                return isset($evento['minor']) && in_array($evento['minor'], [75, 38]);
            });
            // Validar que los eventos tienen las claves 'name' y 'time'
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['cardNo']);
            });
            // Ordenar eventos por hora para procesarlos en orden cronológico desdel el mas reciente al mas antiguo
            usort($eventos, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

            // Agrupar eventos por empleado y fecha
            $eventosAgrupados = [];
            foreach ($eventos as $evento) {
                $fechaEvento = Carbon::parse($evento['time'])->format('Y-m-d');
                $eventosAgrupados[$evento['name']][$fechaEvento][] = $evento;
            }
//            Log::channel('testing')->info('Log', ['sincronizarAsistencias -> eventos agrupados', $eventosAgrupados]);

            foreach ($eventosAgrupados as $nombreEmpleado => $fechas) {
//                Log::channel('testing')->info('Log', ['Eventos agrupados ', $nombreEmpleado, $fechas]);
                $this->guardarEventosEmpleado($fechas);
            }
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['GuzzleException en AsistenciaService::sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    public function guardarEventosEmpleado($eventosDelDia)
    {
        $marcacion = new Marcacion();
        foreach ($eventosDelDia as $dia => $eventos) {
//            Log::channel('testing')->info('Log', ['recorrerEventosEmpleado ', $dia, $eventos]);
            // se tiene el dia y los eventos, falta obtener la cedula del empleado, pero primero filtramos los eventos para eliminar los duplicados en segundos
            $eventosFiltrados = $this->filtrarEventosPorTiempo($eventos);
            // se mapea las fechas de los eventos filtrados, ya que esos registros irán a las marcaciones como un json
            $marcaciones = array_map(function ($evento) {
                return Carbon::parse($evento['time'])->format('H:i:s');
            }, $eventosFiltrados);
            //obtenemos el empleado
            $empleado = Empleado::where('identificacion', $eventosFiltrados[0]['cardNo'])->first();
            if (!$empleado) continue; // se salta ese registro si no hay el cardNo, pero no debería entrar aquí

            $marcacion->empleado_id = $empleado->id;
            $marcacion->fecha = $dia;
            $marcacionExistente = Marcacion::where('empleado_id', $empleado->id)->where('fecha', $dia)->first();
            if ($marcacionExistente)
                $marcacionExistente->update(['marcaciones', $marcaciones]);
            else {
                $marcacion->marcaciones = $marcaciones;
                $marcacion->save();
            }
        }
    }

    /**
     * Filtra los eventos, para eliminar los duplicados, porque los registros del biometrico muchas veces graba
     * dos registros, cara y huella, entonces se trabaja con el primer registro obtenido y el segundo se descarta,
     * a menos que la diferencia sea superior a 1 minuto, lo cual toma como una marcacion valida
     * @param $eventos
     * @return array
     */
    public function filtrarEventosPorTiempo($eventos)
    {
        $resultado = [];
        $ultimoTiempo = null;
        foreach ($eventos as $evento) {
            $tiempoActual = strtotime($evento['time']);
            // Verificar si la diferencia es mayor a 1 minuto
            if ($ultimoTiempo === null || ($tiempoActual - $ultimoTiempo) > 60) {
                $resultado[] = $evento;
                $ultimoTiempo = $tiempoActual;
            }
        }
        return $resultado;
    }
}
