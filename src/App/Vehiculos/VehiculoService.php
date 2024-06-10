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
use App\Models\Vehiculos\TransferenciaVehiculo;
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

    /**
     * La función "verificarDisponibilidadVehiculo" verifica si un vehículo está disponible para
     * asignación a un empleado en función de su estado actual en el sistema.
     * 
     * @param int $vehiculo_id Verifica la disponibilidad de un vehículo en función de su ID. 
     * Primero busca cualquier asignación del vehículo en la tabla `AsignacionVehiculo` donde 
     * el estado es 'ACEPTADO', no transferido y no devuelto.
     * 
     * @return bool Si se cumplen las condiciones tanto para `AsignacionVehiculo` como para
     * `TransferenciaVehiculo`, entonces devuelve `false`, indicando que el vehículo no
     * está disponible. De lo contrario, devuelve "verdadero", lo que indica que el vehículo está
     * disponible para su asignación.
     */
    public function verificarDisponibilidadVehiculo(int $vehiculo_id)
    {
        //Primero se verifica si el vehículo está asignado a alguien por medio de una asignación
        $vehiculoAsignado = AsignacionVehiculo::where('vehiculo_id', $vehiculo_id)
            ->where('estado', AsignacionVehiculo::ACEPTADO)->where('transferido', false)
            ->where('devuelto', false)->orderBy('id', 'desc')->first();
        if ($vehiculoAsignado) return false;
        else {
            // En este caso se verifica si el vehículo está asignado a alguien por medio de una transferencia
            $vehiculoAsignadoPorTransferencia = TransferenciaVehiculo::where('vehiculo_id', $vehiculo_id)
                ->whereIn('estado', [AsignacionVehiculo::ACEPTADO, AsignacionVehiculo::PENDIENTE])
                ->where('transferido', false)->where('devuelto', false)->orderBy('id', 'desc')->first();
            if ($vehiculoAsignadoPorTransferencia) return false;
        }
        return true;
    }

    /**
     * La función actualiza el custodio_id de un vehículo en la base de datos.
     * 
     * @param int $vehiculo_id El parámetro `vehiculo_id` es un número entero que representa el
     * identificador único del vehículo cuyo custodio se está actualizando.
     * @param int|null $custodio_id El parámetro `custodio_id` en la función `actualizarCustodioVehiculo` es
     * el identificador del custodio (responsable del vehículo) que se desea asignar a un vehículo
     * específico. Es de tipo `int|null`, lo que significa que puede configurarse como null si es necesario.
     */
    public function actualizarCustodioVehiculo(int $vehiculo_id, int|null $custodio_id)
    {
        $vehiculo = Vehiculo::find($vehiculo_id);
        if ($vehiculo) {
            $vehiculo->custodio_id = $custodio_id;
            $vehiculo->save();
        }
    }
}

//21E32R43Caerf2234dvg