<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use App\Models\ControlPersonal\Marcacion;
use App\Models\ControlPersonal\OficinaBiometrico;
use App\Models\Empleado;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

// <- corregido
use Illuminate\Support\Facades\Log;
use Tests\Models\Car;

/**
 * TODO: Códigos de minor
 * 0x26 => 38 => Fingerprint Authentication Completed
 * 0x4b => 75 => Face Authentication Completed
 */
class AsistenciaService
{
//    protected Client $client;

    public function __construct()
    {
    }

    /**
     * INFO: Método con el que se está trabajando, consultando desde asistenciaController
     * Este método está implementado en FetchHikVisionRecords
     * Obtiene todos los eventos del mes del biométrico (Napoleón, base actual).
     *
     * @throws GuzzleException
     * @throws Exception
     */
    /*    public function obtenerRegistrosDiarios(): array
        {
            $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
            $startTime = Carbon::now()->startOfMonth()->toIso8601String();
            $endTime   = Carbon::now()->endOfMonth()->toIso8601String();
            /* $startTime = Carbon::now()->subMonth()->startOfMonth()->toIso8601String();
            $endTime = Carbon::now()->subMonth()->endOfMonth()->toIso8601String();
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

                return $eventosTotales;
            } catch (Exception $e) {
                // Manejar errores en caso de falla
                Log::channel('testing')->info('Log', ['Exception en obtenerRegistrosDiarios:', $e->getLine(), $e->getMessage()]);
                throw $e;
            }
        }*/

    /**
     * Mantiene tu flujo actual: Napoleón vía FastAPI.
     *
     * @throws Exception
     */
    public function obtenerRegistrosMesFASTAPI(): array
    {
        try {
            $fastapi = env('FAST_API_URL_DEFAULT');
            $fastapi_apikey = env('API_KEY_FOR_FASTAPI');
            $url = rtrim($fastapi, '/') . '/biometrico-napoleon';

            $response = Http::withHeaders(['x-api-key' => $fastapi_apikey])
                ->withOptions(['verify' => false])
                ->timeout(90)
                ->get($url);

            // Retorna como antes (sin añadir validaciones para no romper nada)
            return $response['eventos'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function parseHikvisionUrls($biometricos)
    {
        $results = [];
        foreach ($biometricos as $biometrico) {
            $url = $biometrico->puerto ? $biometrico->direccion_ip . ':' . $biometrico->puerto : $biometrico->direccion_ip;
            $r['nombre'] = $biometrico->nombre;
            $r['url'] = $url;
            $r['x-api-key'] = $biometrico->clave_acceso;
            $results[] = $r;
        }
//        Log::channel('testing')->info('Hikvision parseHikvisionUrls', [$results]);
        return $results;
    }

    /**
     * NUEVO: parsea HIKVISION_URLS en una lista de URLs normalizadas (con / al final),
     * excluyendo el HIKVISION_BASE_URL si por accidente fue incluido.
     */
    private function parseHikvisionUrlsLegacy(): array
    {
        $raw = env('HIKVISION_URLS', '');
        if (!$raw) return [];

        $urls = array_filter(array_map(function ($u) {
            $u = trim($u);
            if ($u === '') return '';
            // normaliza trailing slash
            if (substr($u, -1) !== '/') {
                $u .= '/';
            }
            return $u;
        }, explode(',', $raw)));

        $base = env('HIKVISION_BASE_URL');
        if ($base && substr($base, -1) !== '/') {
            $base .= '/';
        }

        // quita duplicados y base si estuviera incluida
        $urls = array_values(array_unique(array_filter($urls, function ($u) use ($base) {
            return $u !== '' && $u !== $base;
        })));

        return $urls;
    }

    /**
     * NUEVO: consulta ISAPI de un biométrico (distinto a Napoleón) y devuelve sus eventos del mes.
     *
     * @throws GuzzleException
     */
    private function fetchFromBiometricoLegacy(string $baseUrl): array
    {
        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout' => 10.0,
            'auth' => [env('HIKVISION_USER'), env('HIKVISION_PASSWORD'), 'digest'],
            'verify' => false,
        ]);

        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->endOfMonth()->toIso8601String();
        $maxResults = 30;

        $ascEventCondBase = [
            "searchID" => "1",
            "major" => 5,
            "minor" => 0,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "picEnable" => false,
            "eventAttribute" => "attendance",
            "currentVerifyMode" => "cardOrFaceOrFp",
            "timeReverseOrder" => true,
        ];

        $todos = [];
        $pos = 0;

        do {
            $ascEventCond = $ascEventCondBase + [
                    "searchResultPosition" => $pos,
                    "maxResults" => $maxResults,
                ];

            $resp = $client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
            $data = json_decode($resp->getBody(), true);
            $lista = $data['AcsEvent']['InfoList'] ?? [];

            if (!is_array($lista) || empty($lista)) break;

            // Etiqueta el origen por si luego lo quieres usar (no afecta tu pipeline)
            foreach ($lista as $ev) {
                $ev['device_url'] = rtrim($baseUrl, '/');
                $todos[] = $ev;
            }

            $pos += count($lista);
        } while (count($lista) === $maxResults);
//        Log::channel('testing')->info('Hikvision fetchFromBiometrico->todos', [$todos]);
        return $todos;
    }

    private function fetchFromBiometricoPorDia(array $biometricoRow, Carbon $dia): array
    {

        $client = new Client([
            'base_uri' => $biometricoRow['url'],
            'timeout' => 10.0,
            'auth' => [env('HIKVISION_USER'), env('HIKVISION_PASSWORD'), 'digest'],
            'verify' => false,
        ]);

        $startTime = $dia->copy()->startOfDay()->toIso8601String();
        $endTime = $dia->copy()->endOfDay()->toIso8601String();

        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $maxResults = 30;

        $ascEventCondBase = [
            "searchID" => "1",
            "major" => 5,
            "minor" => 0,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "picEnable" => false,
            "eventAttribute" => "attendance",
            "currentVerifyMode" => "cardOrFaceOrFp",
            "timeReverseOrder" => true,
        ];

        $todos = [];
        $pos = 0;

        do {
            $ascEventCond = $ascEventCondBase + [
                    "searchResultPosition" => $pos,
                    "maxResults" => $maxResults,
                ];
            if (isset($biometricoRow['x-api-key'])) {
                $response = Http::withHeaders(['x-api-key' => $biometricoRow['x-api-key']])
                    ->withOptions(['verify' => false])
                    ->timeout(90)
                    ->get($biometricoRow['url'], [
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                    ]);
                $lista = $response['eventos'];
            } else {
                $resp = $client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
                $data = json_decode($resp->getBody(), true);
                $lista = $data['AcsEvent']['InfoList'] ?? [];
            }

            if (!is_array($lista) || empty($lista)) break;

            // Etiqueta el origen por si luego lo quieres usar (no afecta tu pipeline)
            foreach ($lista as $ev) {
                $ev['biometrico'] = trim($biometricoRow['nombre']);
                $todos[] = $ev;
            }

            $pos += count($lista);
        } while (count($lista) === $maxResults);
        Log::channel('testing')->info('Hikvision fetchFromBiometrico->todos', [$biometricoRow['nombre'], $dia, array_filter($todos, function ($evento) {
            return isset($evento['minor']) && in_array($evento['minor'], [75, 38]);
        })]);
        return $todos;
    }


    private function fetchFromBiometrico(array $biometricoRow): array
    {

        $client = new Client([
            'base_uri' => $biometricoRow['url'],
//            'base_uri' => $baseUrl,
            'timeout' => 10.0,
            'auth' => [env('HIKVISION_USER'), env('HIKVISION_PASSWORD'), 'digest'],
            'verify' => false,
        ]);

        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->startOfMonth()->endOfDay()->toIso8601String();
        $maxResults = 30;

        $ascEventCondBase = [
            "searchID" => "1",
            "major" => 5,
            "minor" => 0,
            "startTime" => $startTime,
            "endTime" => $endTime,
            "picEnable" => false,
            "eventAttribute" => "attendance",
            "currentVerifyMode" => "cardOrFaceOrFp",
            "timeReverseOrder" => true,
        ];

        $todos = [];
        $pos = 0;

        do {
            $ascEventCond = $ascEventCondBase + [
                    "searchResultPosition" => $pos,
                    "maxResults" => $maxResults,
                ];
            if (isset($biometricoRow['x-api-key'])) {
                $response = Http::withHeaders(['x-api-key' => $biometricoRow['x-api-key']])
                    ->withOptions(['verify' => false])
                    ->timeout(90)
                    ->get($biometricoRow['url']);
                $lista = $response['eventos'];
            } else {
                $resp = $client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
                $data = json_decode($resp->getBody(), true);
                $lista = $data['AcsEvent']['InfoList'] ?? [];
            }

            if (!is_array($lista) || empty($lista)) break;

            // Etiqueta el origen por si luego lo quieres usar (no afecta tu pipeline)
            foreach ($lista as $ev) {
                $ev['biometrico'] = trim($biometricoRow['nombre']);
                $todos[] = $ev;
            }

            $pos += count($lista);
        } while (count($lista) === $maxResults);
        Log::channel('testing')->info('Hikvision fetchFromBiometrico->todos', [$biometricoRow['nombre'], $todos]);
        return $todos;
    }


    /**
     * NUEVO: recolecta eventos de TODOS los biométricos listados en HIKVISION_URLS (excepto Napoleón).
     */
    public function obtenerRegistrosOtrosBiometricos(): array
    {
        $urls = $this->parseHikvisionUrls();
        if (empty($urls)) return [];

        $acumulado = [];
        foreach ($urls as $url) {
            try {
                $acumulado = array_merge($acumulado, $this->fetchFromBiometrico($url));
            } catch (Exception $e) {
                Log::channel('testing')->warning('Hikvision extra falló', [
                    'url' => $url,
                    'line' => $e->getLine(),
                    'msg' => $e->getMessage(),
                ]);
                // continúa con los demás
            }
        }
        return $acumulado;
    }

    public function obtenerRegistrosOficinasBiometricos(): array
    {
        $biometricos = OficinaBiometrico::where('activo', true)->get();
        $urls = $this->parseHikvisionUrls($biometricos);
        if (empty($urls)) return [];

        $acumulado = [];
        foreach ($urls as $url) {
            try {
                $hoy = Carbon::parse('2025-12-05');
                $acumulado = array_merge($acumulado, $this->fetchFromBiometricoPorDia($url, $hoy));
            } catch (Exception $e) {
                Log::channel('testing')->warning('Hikvision extra falló', [
                    'url' => $url,
                    'line' => $e->getLine(),
                    'msg' => $e->getMessage(),
                ]);
                // continúa con los demás
            }
        }
        Log::channel('testing')->info('Hikvision obtener todos los registros', [$acumulado]);
        return $acumulado;
    }


    /**
     * NUEVO: fusiona Napoleón (vía FastAPI) + otros biométricos (vía ISAPI múltiple).
     *
     * @throws Exception
     */
    public function obtenerRegistrosTodosBiometricosLegacy(): array
    {
        $napoleon = $this->obtenerRegistrosMesFASTAPI(); // se mantiene tu forma actual
        $otros = $this->obtenerRegistrosOtrosBiometricos();
        return array_merge($napoleon, $otros);
    }

    public function obtenerRegistrosTodosBiometricos(): array
    {
        return $this->obtenerRegistrosOficinasBiometricos();
    }

    /**
     * Sincroniza asistencias desde todas las fuentes.
     * CAMBIO MÍNIMO: usa obtenerRegistrosTodosBiometricos() en lugar de solo FastAPI.
     *
     * @throws Exception
     */
    public function sincronizarAsistencias()
    {
        try {
            // Antes: $datos = $this->obtenerRegistrosMesFASTAPI();
            $datos = $this->obtenerRegistrosTodosBiometricos();

            // De los eventos recibidos filtramos para obtener solo los eventos con 'minor' igual a 75 o 38
            $eventos = array_filter($datos, function ($evento) {
                return isset($evento['minor']) && in_array($evento['minor'], [75, 38]);
            });
            // Validar que los eventos tienen las claves 'cardNo'
            $eventos = array_filter($eventos, function ($evento) {
                return isset($evento['cardNo']);
            });

            // Ordenar eventos por hora para procesarlos en orden cronológico desde el más reciente al más antiguo
            usort($eventos, fn($a, $b) => strtotime($a['time']) - strtotime($b['time']));

            // Agrupar eventos por empleado y fecha (mantengo tu agrupación por name)
            $eventosAgrupados = [];
            foreach ($eventos as $evento) {
                $fechaEvento = Carbon::parse($evento['time'])->format('Y-m-d');
                $eventosAgrupados[$evento['name']][$fechaEvento][] = $evento;
            }
//            Log::channel('testing')->info('Eventos agrupados', [$eventosAgrupados]);
            foreach ($eventosAgrupados as $nombreEmpleado => $fechas) {
                $this->guardarEventosEmpleado($fechas);
            }
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['GuzzleException en AsistenciaService::sincronizarAsistencias:', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    public function guardarEventosEmpleado($eventosDelDia)
    {
        foreach ($eventosDelDia as $dia => $eventos) {
            // se tiene el dia y los eventos, falta obtener la cedula del empleado, pero primero filtramos los eventos para eliminar los duplicados en segundos
            $eventosFiltrados = $this->filtrarEventosPorTiempo($eventos);

//            Log::channel('testing')->info('Eventos filtrados', [$eventosFiltrados]);
            // se mapea las fechas de los eventos filtrados, ya que esos registros irán a las marcaciones como un json
            $marcacionesNuevas = array_map(function ($evento) {
                return [$evento['biometrico'] => Carbon::parse($evento['time'])->format('H:i:s')];
            }, $eventosFiltrados);
//            Log::channel('testing')->info('marcaciones', [$marcaciones]);
            // obtenemos el empleado
            if (empty($eventosFiltrados)) continue;


            $empleado = Empleado::where('identificacion', $eventosFiltrados[0]['cardNo'])->first();
            if (!$empleado) continue; // se salta ese registro si no hay el cardNo, pero no debería entrar aquí

            $marcacionExistente = Marcacion::where('empleado_id', $empleado->id)->where('fecha', $dia)->first();
            if ($marcacionExistente) {
                // Unir marcaciones nuevas con existentes
                $marcacionExistente->update(['marcaciones' => $this->normalizarMarcaciones($marcacionExistente, $marcacionesNuevas)]);
            } else {
                $marcacion = new Marcacion();
                $marcacion->empleado_id = $empleado->id;
                $marcacion->fecha = $dia;
                $marcacion->marcaciones = $marcacionesNuevas;
                $marcacion->save();
            }
        }
    }

    /**
     * Normaliza las marcaciones para no sobreescribir el array de marcaciones existentes,
     * sino agregar nuevas siempre que llegue una nueva
     * y si llega con alguna marcacion incompleta no se borre de las ya existentes.
     * @param Marcacion $marcacionExistente
     * @param array $marcacionesNuevas
     * @return array
     */
    private function normalizarMarcaciones(Marcacion $marcacionExistente, array $marcacionesNuevas)
    {
        $marcacionesPrevias = $marcacionExistente->marcaciones ??[];
        $unidas = array_merge($marcacionesPrevias, $marcacionesNuevas);

        // Quitar duplicados por valor de hora
        $hash = [];
        $final = [];
        foreach ($unidas as $u) {
            $biometrico = array_keys($u)[0];
            $hora = $u[$biometrico];
            $key = $biometrico."_".$hora;

            if(!isset($hash[$key])) {
                $hash[$key] = true;
                $final[] = $u;
            }
        }

        // ordenamos cronologicamente
        usort($final, function ($a, $b) {
            $horaA = reset($a);
            $horaB = reset($b);
            return strcmp($horaA, $horaB);
        });

        return $final;
    }

    /**
     * Filtra los eventos, para eliminar los duplicados, porque los registros del biométrico muchas veces graba
     * dos registros, cara y huella, entonces se trabaja con el primer registro obtenido y el segundo se descarta,
     * a menos que la diferencia sea superior a 1 minuto, lo cual toma como una marcación válida
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
