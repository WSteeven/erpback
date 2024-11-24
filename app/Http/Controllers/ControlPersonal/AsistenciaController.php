<?php

namespace App\Http\Controllers\ControlPersonal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Empleado;
use Carbon\Carbon;
use GuzzleHttp\Client;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $asistencias = Asistencia::with('empleado')->get();
        return response()->json($asistencias);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos proporcionados en el request
        $validatedData = $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*.employeeName' => 'required|string',
            'asistencias.*.startTime' => 'required|date',
        ]);

        // Registrar asistencias
        foreach ($validatedData['asistencias'] as $evento) {
            $empleado = Empleado::whereRaw("CONCAT(nombres, ' ', apellidos) = ?", [$evento['employeeName']])->first();

            if ($empleado) {
                Asistencia::updateOrCreate(
                    [
                        'empleado_id' => $empleado->id,
                        'hora_ingreso' => $evento['startTime'],
                    ],
                    [
                        'hora_salida' => $evento['endTime'] ?? null,
                        'hora_salida_almuerzo' => $evento['lunchOutTime'] ?? null,
                        'hora_entrada_almuerzo' => $evento['lunchInTime'] ?? null,
                    ]
                );
            }
        }

        return response()->json(['message' => 'Asistencias registradas correctamente.']);
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
            return response()->json(['message' => 'Asistencia no encontrada.'], 404);
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
            return response()->json(['message' => 'Asistencia no encontrada.'], 404);
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
            return response()->json(['message' => 'Asistencia no encontrada.'], 404);
        }

        $asistencia->delete();

        return response()->json(['message' => 'Asistencia eliminada correctamente.']);
    }

    /**
     * Sincronizar asistencias desde el biométrico.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sincronizarAsistencias()
    {
        try {
            $datos = $this->consultarBiometrico();

            foreach ($datos as $evento) {
                $empleado = Empleado::whereRaw("CONCAT(nombres, ' ', apellidos) = ?", [$evento['employeeName']])->first();

                if ($empleado) {
                    Asistencia::updateOrCreate(
                        [
                            'empleado_id' => $empleado->id,
                            'hora_ingreso' => $evento['startTime'],
                        ],
                        [
                            'hora_salida' => $evento['endTime'] ?? null,
                            'hora_salida_almuerzo' => $evento['lunchOutTime'] ?? null,
                            'hora_entrada_almuerzo' => $evento['lunchInTime'] ?? null,
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
     * Consultar datos desde la API del biométrico.
     *
     * @return array
     */
    private function consultarBiometrico()
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
}
