<?php

namespace Src\App\Vehiculos;

use App\Http\Resources\Vehiculos\AsignacionVehiculoResource;
use App\Http\Resources\Vehiculos\MantenimientoVehiculoResource;
use App\Http\Resources\Vehiculos\OrdenReparacionResource;
use App\Http\Resources\Vehiculos\RegistroIncidenteResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\Autorizacion;
use App\Models\Vehiculos\AsignacionVehiculo;
use App\Models\Vehiculos\MantenimientoVehiculo;
use App\Models\Vehiculos\OrdenReparacion;
use App\Models\Vehiculos\RegistroIncidente;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VehiculoService
{

    public function __construct()
    {
    }

    public function obtenerHistorial(Vehiculo $vehiculo, Request $request)
    {
        $results = [];
        $resultados = [];
        $fecha_inicio = date('Y-m-d', strtotime($request->fecha_inicio));
        $fecha_fin = date('Y-m-d', strtotime($request->fecha_fin));
        try {
            $results['vehiculo'] = new VehiculoResource($vehiculo);
            if (count($request->opciones) > 0) {
                foreach ($request->opciones as $opcion) {
                    switch ($opcion) {
                        case Vehiculo::CUSTODIOS:
                            $resultados = $this->obtenerCustodios($vehiculo, $fecha_inicio, $fecha_fin);
                            break;
                        case Vehiculo::MANTENIMIENTOS:
                            $resultados = $this->obtenerMantenimientos($vehiculo, $fecha_inicio, $fecha_fin);
                            break;
                        case Vehiculo::INCIDENTES:
                            $resultados = $this->obtenerIncidentes($vehiculo, $fecha_inicio, $fecha_fin);
                            break;
                        default:
                            $results['custodios'] = $this->obtenerCustodios($vehiculo, $fecha_inicio, $fecha_fin);
                            $results['mantenimientos'] = $this->obtenerMantenimientos($vehiculo, $fecha_inicio, $fecha_fin);
                            $results['incidentes'] = $this->obtenerIncidentes($vehiculo, $fecha_inicio, $fecha_fin);
                            return $results;
                    }
                    $results[strtolower($opcion)] = $resultados;
                }
            }
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en obtenerHistorial', $th->getLine()]);
            throw $th;
        }
        return $results;
    }

    private function obtenerCustodios($vehiculo, $fecha_inicio, $fecha_fin)
    {
        $results = [];
        try {
            $asignaciones = AsignacionVehiculo::where('vehiculo_id', $vehiculo->id)
                ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
            // Log::channel('testing')->info('Log', ['Asignaciones', $asignaciones]);
            $results = AsignacionVehiculoResource::collection($asignaciones);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en obtenerCustodios', $th->getLine()]);
            throw $th;
        }
        return $results;
    }
    private function obtenerMantenimientos($vehiculo, $fecha_inicio, $fecha_fin)
    {
        $results = [];
        try {
            //listamos los mantenimientos preventivos en orden de creacion de mayor a menor
            $mantenimientos = MantenimientoVehiculo::where('vehiculo_id', $vehiculo->id)
                ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
            //listamos los mantenimientos correctivos
            $ordenes = OrdenReparacion::where('vehiculo_id', $vehiculo->id)
                ->whereIn('autorizacion_id', [Autorizacion::PENDIENTE_ID, Autorizacion::APROBADO_ID])
                ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
            $results['programados'] = MantenimientoVehiculoResource::collection($mantenimientos);
            $results['correctivos'] = OrdenReparacionResource::collection($ordenes);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en obtenerMantenimientos', $th->getLine()]);
            throw $th;
        }
        return $results;
    }
    private function obtenerIncidentes($vehiculo, $fecha_inicio, $fecha_fin)
    {
        $results = [];
        try {
            $incidentes = RegistroIncidente::where('vehiculo_id', $vehiculo->id)
                ->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->get();
            $results = RegistroIncidenteResource::collection($incidentes);
        } catch (\Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en obtenerIncidentes', $th->getLine()]);
            throw $th;
        }
        return $results;
    }
}

//21E32R43Caerf2234dvg