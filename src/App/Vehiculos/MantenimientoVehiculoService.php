<?php

namespace Src\App\Vehiculos;

use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\MantenimientoVehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;

class MantenimientoVehiculoService
{
    private Empleado $admin_vehiculos;
    public function __construct()
    {
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS, true);
    }

    /**
     * La función "obtenerItemMantenimiento" filtra elementos del plan de mantenimiento según el ID del vehículo y del servicio.
     * 
     * @param Collection $items Cada elemento de la
     * colección probablemente tenga propiedades como `servicio_id` y `vehiculo_id` que se utilizan
     * para filtrar y recuperar elementos de mantenimiento específicos.
     * @param int $vehiculo El id de vehiculo
     * @param int $servicio El id de `servicio`
     * 
     * @return PlanMantenimiento $item Se está devolviendo el artículo que coincide
     * con `servicio_id` y `vehiculo_id`.
     */
    public function obtenerItemMantenimiento($items, $vehiculo, $servicio)
    {
        $item = $items->filter(function ($item) use ($vehiculo, $servicio) {
            return $item->servicio_id == $servicio && $item->vehiculo_id == $vehiculo;
        })->first();
        return $item;
    }

    /**
     * La función `actualizarMantenimiento` en PHP actualiza los registros de mantenimiento en función
     * del kilometraje del vehículo y el historial de mantenimiento anterior.
     * 
     * @param $bitacora El parámetro `bitacora` parece representar un registro o registro de algún tipo,
     * posiblemente relacionado con actividades de mantenimiento del vehículo. Probablemente contenga
     * información como los kilómetros iniciales y finales de un vehículo durante un período
     * específico.
     * @param $itemPlan  es una variable que probablemente contenga información sobre un plan
     * de mantenimiento para un vehículo. Puede incluir detalles como el intervalo en el que se debe
     * realizar el mantenimiento, el punto de partida para aplicar el mantenimiento y otros datos
     * relevantes para programar y rastrear el mantenimiento del vehículo.
     * @param $mantenimiento La función `actualizarMantenimiento` que proporcionó parece estar
     * actualizando los registros de mantenimiento de un vehículo en función de ciertas condiciones. El
     * parámetro `mantenimiento` en esta función representa el registro de mantenimiento que necesita
     * ser actualizado.
     */
    public function actualizarMantenimiento($bitacora, $itemPlan, $mantenimiento)
    {
        try {
            DB::beginTransaction();
            $ultimoMantenimiento = MantenimientoVehiculo::where('vehiculo_id', $mantenimiento->vehiculo_id)->where('servicio_id', $mantenimiento->servicio_id)->where('estado', MantenimientoVehiculo::REALIZADO)->orderBy('id', 'desc')->first();
            if ($ultimoMantenimiento) {
                //Se realiza el calculo en base al km_realizado del ultimo mantenimiento
                if ($bitacora->km_final > $ultimoMantenimiento->km_realizado + $itemPlan->aplicar_cada) {
                    $mantenimiento->estado = MantenimientoVehiculo::RETRASADO;
                    $mantenimiento->km_retraso = $bitacora->km_final - ($ultimoMantenimiento->km_realizado + $itemPlan->aplicar_cada);
                    $mantenimiento->save();
                }
            } else {
                //Se realiza el calculo como que fuera el primer mantenimiento
                if ($bitacora->km_final > ($itemPlan->aplicar_desde + $itemPlan->aplicar_cada)) {
                    $mantenimiento->estado = MantenimientoVehiculo::RETRASADO;
                    $mantenimiento->km_retraso = $bitacora->km_final - ($itemPlan->aplicar_desde + $itemPlan->aplicar_cada);
                    $mantenimiento->save();
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error en actualizarMantenimiento ', $th->getLine()]);
            throw $th;
        }
    }
}
