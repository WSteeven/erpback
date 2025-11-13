<?php

namespace App\Http\Controllers\Hunter;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hunter\PosicionHunterResource;
use App\Models\Grupo;
use App\Models\Hunter\PosicionHunter;
use App\Models\Vehiculos\Vehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\Hunter\PosicionHunterService;

class PosicionHunterController extends Controller
{

    public function posicionesHunter(Request $request)
    {
        $mensaje = 'No se procesaron datos.';
        $savedCount = 0;
        try {
//        Log::channel('testing')->info('Log', ['Recibido en la request posicionesHunter', $request->all()]);
            $data = $request->all();
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
                    'encendido' => (bool)$vehiculo['tipoReporte'][0],
                    'direccion' => $vehiculo['direccion'],
                    'tipo_reporte' => $vehiculo['tipoReporte'],
//                    'tipo_reporte' => trim(preg_replace('/^[01]\s*/', '', $vehiculo['tipoReporte'])),
                    'estado' => $vehiculo['estado'],
                    'flags_binarios' => is_null($vehiculo['flagsBinarios']) ? [] : json_encode($vehiculo['flagsBinarios']),
                    'flags' => $vehiculo['flags'],
                    'raw_data' => $vehiculo['raw'],
                    'received_at' => $data['receivedAt'],
                    // 'location' se llena por trigger
                ]);

//                event(new NuevaPosicionHunterEvent($posicion));

                $savedCount++;
            }
            $mensaje = "Datos recibidos y almacenados con éxito en hunter. $savedCount actividades procesadas.";
//            Log::channel('testing')->info('Log', ['Mensaje', $mensaje]);
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

    public function ubicacionesGPSconTareas()
    {
        $posiciones = PosicionHunter::latestPerVehicle()->orderBy('placa')->get();

        $results = PosicionHunterResource::collection($posiciones);
        $idsPlacas = $posiciones->pluck('placa')->toArray();
//        Log::channel('testing')->info('Log', ['ubicacionesGPSconTareas->placas', $idsPlacas]);
        $idsVehiculos = Vehiculo::whereIn('placa', $idsPlacas)->pluck('id');

        $gruposRelacionados = Grupo::whereIn('vehiculo_id', $idsVehiculos)->where('activo', true)->get();

        // 5. TAREAS SIN GRUPO (las que NO tienen source que coincida con ningún grupo)
        $tareasSinGrupo = PosicionHunterService::obtenerTareasSinGrupo($gruposRelacionados);

        $gruposMapeados = PosicionHunterService::mapearGruposVehiculosTareas($gruposRelacionados, $posiciones);

        return response()->json(compact('results', 'gruposMapeados', 'tareasSinGrupo'));
    }
}
