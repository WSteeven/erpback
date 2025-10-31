<?php

namespace App\Http\Controllers\Hunter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hunter\PosicionHunterResource;
use App\Models\Hunter\PosicionHunter;
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
//        Log::channel('testing')->info('Log', ['Recibido en la request posicionesHunter2', $request->all()]);
        $data = $request->all();
        $savedCount = 0;
        $vehiculos = $data['vehicles'];
        foreach ($vehiculos as $vehiculo) {
            // En tu servicio o controlador
            PosicionHunter::create([
                'source' => $data['source'],
                'imei' => $vehiculo['imei'],
                'placa' => $vehiculo['placa'],
                'lat' => $vehiculo['lat'],
                'lng' => $vehiculo['lng'],
                'velocidad' => $vehiculo['velocidad'],
                'rumbo' => $vehiculo['rumbo'],
                'alt' => $vehiculo['alt'],
                'fecha' => $vehiculo['fecha'],
                'encendido' => $vehiculo['encendido'],
                'direccion' => $vehiculo['direccion'],
                'tipo_reporte' => $vehiculo['tipoReporte'],
                'estado' => $vehiculo['estado'],
                'flags_binarios' => $vehiculo['flagsBinarios'],
                'flags' => $vehiculo['flags'],
                'raw_data' => $vehiculo['raw'],
                'received_at' => $data['receivedAt'],
                // 'location' se llena por trigger
            ]);

            $savedCount++;
        }
        $mensaje = "Datos recibidos y almacenados con éxito en hunter. {$savedCount} actividades procesadas.";
        Log::channel('testing')->info('Log', ['Mensaje', $mensaje]);
        return response()->json(compact('mensaje', 'savedCount'));
    }

    public function ubicacionesGPS()
    {
        $posiciones = PosicionHunter::latestPerVehicle()->orderBy('placa')->get();

        $results = PosicionHunterResource::collection($posiciones);
        return response()->json(compact('results'));

    }
}
