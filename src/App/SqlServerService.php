<?php

namespace Src\App;

use Carbon\Carbon;
use DB;
use Exception;
use Http;
use Illuminate\Support\Facades\Log;

class SqlServerService
{
    //schema=dbo
    //tabla=BITACORA_EC

    public function __construct()
    {
    }


    /**
     * @throws Exception
     */
    public static function obtenerRegistros(string $fechaInicio, string $fechaFin)
    {
        $fastapi = env('FAST_API_URL_DEFAULT');
        $fastapi_apikey = env('API_KEY_FOR_FASTAPI');
        $url = $fastapi.'asistencias';

        $response =  Http::withHeaders(['x-api-key'=>$fastapi_apikey])->withOptions(['verify'=>false])
            ->acceptJson()
            ->get($url, ['fecha_inicio'=>$fechaInicio, 'fecha_fin'=>$fechaFin]);

        return $response->json();
    }
    public static function obtenerRegistrosOld(string|Carbon $fechaInicio, string|Carbon $fechaFin)
    {
        $resultados = DB::connection('sqlsrv_external')
            ->table('BITACORA_EC')
            ->select([
                'ItemNo',
                'RowId',
                'StartTime',
                'ReceivedTime',
                'CompleteTime',
                'UserFirstName',
                'UserLastName',
                'Informe',
                'FechaInicioTurno',
                'NOC',
                'SubZona',
                'Cuadrilla',
                'Prueba_Nombre',
                'FHInicioBitacora',
                'Direccion',
                'TipoMantenimiento',
                'TipoActividad',
                'ObservacionesHallazgo',
                'FHFinBitacora',
                'FechaID',
                'Tiempo_Capturado_Fin',
                'FHInicioMtto',
                'FHFinMtto',
                '_lastupdated'
            ])
            ->where('UserLastName', 'C.LTDA')
            ->whereRaw("FechaInicioTurno >= CONVERT(datetime, ?, 120)", [$fechaInicio])
            ->whereRaw("FechaInicioTurno < CONVERT(datetime, ?, 120)", [$fechaFin])
            ->orderByDesc('FechaInicioTurno')
            ->get();

        return self::mapToUTF8($resultados);
    }


    private static function mapToUTF8($registros)
    {
        // Recorremos los registros y convertimos todo a UTF-8
        return $registros->map(function ($item) {
            foreach ($item as $key => $value) {
                if (is_string($value)) {
                    $item->$key = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
                }
            }
            return $item;
        });
    }

}
