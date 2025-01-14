<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

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
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('HIKVISION_BASE_URL'),
            'timeout' => 10.0,
            'auth' => [env('HIKVISION_USER'), env('HIKVISION_PASSWORD'), 'digest'],
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function obtenerRegistrosDiarios24Mayo()
    {
        Log::channel('testing')->info('Log', ['obtenerRegistrosDiarios24Mayo:']);
        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->endOfMonth()->toIso8601String();
        $maxResults = 800;
        $searchResultPosition = 0;
        $eventosTotales = [];

        do {
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
                "timeReverseOrder" => true
            ];

            $response = $this->client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
            //$response = $this->client->post(  $base_uri.'/'.$endpoint, ['verify'=>false,"json" => ["AcsEventCond" => $ascEventCond]]);
            $data = json_decode($response->getBody(), true);

            if (isset($data['AcsEvent']['InfoList'])) {
                $eventosTotales = array_merge($eventosTotales, $data['AcsEvent']['InfoList']);
                $searchResultPosition += $maxResults;
            } else {
                break; // Salir si no hay más eventos
            }
        } while (count($data['AcsEvent']['InfoList'] ?? []) === $maxResults);

        return ['AcsEvent' => ['InfoList' => $eventosTotales]];
    }

    /**
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

            return ['AcsEvent' => ['InfoList' => $eventosTotales]];
        } catch (Exception $e) {
            // Manejar errores en caso de falla
            Log::channel('testing')->info('Log', ['Exception en obtenerRegistrosDiarios:', $e->getLine(), $e->getMessage()]);
            throw $e;
        }
    }

    public function consultarBiometrico()
    {
        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->endOfMonth()->toIso8601String();
        $maxResults = 1000;
        $searchResultPosition = 0;
        $eventosTotales = [];

        do {
            $ascEventCond = [
                "searchID" => "1",
                "searchResultPosition" => $searchResultPosition,
                "maxResults" => $maxResults,
                "major" => 5,
                "minor" => 0,
                "startTime" => $startTime,
                "endTime" => $endTime,
                "picEnable" => false,
                "timeReverseOrder" => true
            ];

            $response = $this->client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
            $data = json_decode($response->getBody(), true);

            if (isset($data['AcsEvent']['InfoList'])) {
                $eventosTotales = array_merge($eventosTotales, $data['AcsEvent']['InfoList']);
                $searchResultPosition += $maxResults;
            } else {
                break;
            }
        } while (count($data['AcsEvent']['InfoList'] ?? []) === $maxResults);

        return ['AcsEvent' => ['InfoList' => $eventosTotales]];
    }
}
