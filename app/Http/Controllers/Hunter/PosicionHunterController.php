<?php

namespace App\Http\Controllers\Hunter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hunter\PosicionHunterResource;
use App\Models\Hunter\PosicionHunter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PosicionHunterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function posicionesHunter(Request $request)
    {
        try {
//        Log::channel('testing')->info('Log', ['Recibido en la request posicionesHunter2', $request->all()]);
            $data = $request->all();
            $savedCount = 0;
            $mensaje = 'No se procesaron datos.';
            $vehiculos = $data['vehicles'];
            foreach ($vehiculos as $vehiculo) {
                // En tu servicio o controlador
                $posicion = PosicionHunter::create([
                    'source' => $data['source'],
                    'imei' => $vehiculo['imei'],
                    'placa' => $vehiculo['placa'],
                    'lat' => $vehiculo['lat'],
                    'lng' => $vehiculo['lng'],
                    'velocidad' => $vehiculo['velocidad'],
                    'rumbo' => $vehiculo['rumbo'],
                    'alt' => $vehiculo['alt'],
                    'fecha' => $vehiculo['fecha'],
                    'encendido' => (bool)$vehiculo['tipoReporte'][0],
                    'direccion' => $vehiculo['direccion'],
                    'tipo_reporte' =>$vehiculo['tipoReporte'],
//                    'tipo_reporte' => trim(preg_replace('/^[01]\s*/', '', $vehiculo['tipoReporte'])),
                    'estado' => $vehiculo['estado'],
                    'flags_binarios' => $vehiculo['flagsBinarios'],
                    'flags' => $vehiculo['flags'],
                    'raw_data' => $vehiculo['raw'],
                    'received_at' => $data['receivedAt'],
                    // 'location' se llena por trigger
                ]);

//                event(new NuevaPosicionHunterEvent($posicion));

                $savedCount++;
            }
            $mensaje = "Datos recibidos y almacenados con éxito en hunter. {$savedCount} actividades procesadas.";
            Log::channel('testing')->info('Log', ['Mensaje', $mensaje]);
        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['Error', $e->getLine() . ' ' . $e->getMessage()]);
        }
        return response()->json(compact('mensaje', 'savedCount'));
    }

    public function ubicacionesGPS()
    {
        $posiciones = PosicionHunter::latestPerVehicle()->orderBy('placa')->get();

        $results = PosicionHunterResource::collection($posiciones);
        return response()->json(compact('results'));

    }
}
