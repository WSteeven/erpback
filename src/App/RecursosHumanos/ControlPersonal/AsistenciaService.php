<?php

namespace Src\App\RecursosHumanos\ControlPersonal;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * TODO: Codigos de minor
 * TODO: 0x45 => 69 => Employee ID and Fingerprint Authentication Completed
 * TODO: 0x48 => 72 => Employee ID and Fingerprint and Password Authentication Completed
 * TODO: 0x4b => 75 => Face Authentication Completed
 * TODO: 0x69 => 105 => Person and ID Card Matched
 * TODO: 0x98 => 152 => Employee ID Not Exists
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
        $endpoint = 'ISAPI/AccessControl/AcsEvent?format=json';
        $startTime = Carbon::now()->startOfMonth()->toIso8601String();
        $endTime = Carbon::now()->endOfMonth()->toIso8601String();
        $maxResults = 400; 
        $searchResultPosition = 0;
        $eventosTotales = [];

        do {
            $ascEventCond = [
                "searchID" => "1",
                "searchResultPosition" => $searchResultPosition,
                "maxResults" => $maxResults,
                "major" => 5,
                "minor" => 75,
                "startTime" => $startTime,
                "endTime" => $endTime,
                "picEnable" => false,
                "eventAttribute" => "attendance",
                "timeReverseOrder" => true
            ];

            $response = $this->client->post($endpoint, ["json" => ["AcsEventCond" => $ascEventCond]]);
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
     * Consultar datos desde la API del biométrico.
     *
     * @return array
     */
    public
    function consultarBiometrico()
    {
        $url = 'http://186.101.253.242/ISAPI/AccessControl/AcsEvent?format=json';
        $username = 'admin';
        $password = 'abc12345';
        $nc = '00000001';
        $cnonce = bin2hex(random_bytes(8));

        $client = new Client(['http_errors' => false]);
        $initialResponse = $client->request('GET', $url);
        $authHeader = $initialResponse->getHeader('WWW-Authenticate')[0];

        preg_match('/realm="([^"]+)"/', $authHeader, $realmMatch);
        preg_match('/nonce="([^"]+)"/', $authHeader, $nonceMatch);
        preg_match('/qop="([^"]+)"/', $authHeader, $qopMatch);

        $realm = $realmMatch[1];
        $nonce = $nonceMatch[1];
        $qop = $qopMatch[1];

        $digestHeader = $this->createDigestHeader($username, $password, 'POST', '/ISAPI/AccessControl/AcsEvent?format=json', $realm, $nonce, $qop, $nc, $cnonce);

        $response = $client->request('POST', $url, [
            'json' => [
                'AcsEventCond' => [
                    'searchID' => "1",
                    'searchResultPosition' => 0,
                    'maxResults' => 500,
                    'major' => 5,
                    'minor' => 75,
                    'startTime' => Carbon::now()->startOfMonth()->toIso8601String(),
                    'endTime' => Carbon::now()->toIso8601String(),
                ],
            ],
            'headers' => [
                'Authorization' => $digestHeader,
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['AcsEvent']['attendance'] ?? [];
    }

    private
    function createDigestHeader($username, $password, $method, $uri, $realm, $nonce, $qop, $nc, $cnonce)
    {
        $ha1 = md5("{$username}:{$realm}:{$password}");
        $ha2 = md5("{$method}:{$uri}");
        $response = md5("{$ha1}:{$nonce}:{$nc}:{$cnonce}:{$qop}:{$ha2}");

        return "Digest username=\"{$username}\", realm=\"{$realm}\", nonce=\"{$nonce}\", uri=\"{$uri}\", qop={$qop}, nc={$nc}, cnonce=\"{$cnonce}\", response=\"{$response}\"";
    }
}


