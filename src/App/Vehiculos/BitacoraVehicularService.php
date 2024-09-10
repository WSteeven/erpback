<?php

namespace Src\App\Vehiculos;

use App\Events\Vehiculos\NotificarAdvertenciasVehiculoBitacora;
use App\Events\Vehiculos\NotificarBajoNivelCombustible;
use App\Events\vehiculos\NotificarDiferenciaKmToAdmin;
use App\Events\vehiculos\NotificarMantenimientoCreado;
use App\Events\Vehiculos\NotificarMantenimientoPendienteRetrasadoEvent;
use App\Http\Resources\Vehiculos\BitacoraVehicularResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\ChecklistAccesoriosVehiculo;
use App\Models\Vehiculos\ChecklistImagenVehiculo;
use App\Models\Vehiculos\ChecklistVehiculo;
use App\Models\Vehiculos\MantenimientoVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\EmpleadoService;
use Src\App\PolymorphicGenericService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class BitacoraVehicularService
{
    private Empleado $admin_vehiculos;
    private PolymorphicGenericService $polymorphicGenericService;
    private MantenimientoVehiculoService $mantenimientoService;

    /**
     * @throws Throwable
     */
    public function __construct()
    {
        $this->polymorphicGenericService = new PolymorphicGenericService();
        $this->admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS);
        $this->mantenimientoService = new MantenimientoVehiculoService();
    }


    public function notificarDiferenciasKmBitacoras(BitacoraVehicular $bitacora)
    {
        $penultimaBitacora = BitacoraVehicular::where('vehiculo_id', $bitacora->vehiculo_id)
            ->whereNot('id', $bitacora->id)
            ->orderBy('id', 'desc')->first();
        if ($penultimaBitacora) {
            //Aquí pondemos una holgura de 3km para calcular la diferencia en el km
            // (156 + 3) < 180 =true
            if (($penultimaBitacora->km_final+3) < $bitacora->km_inicial) {
                //Notificar al admin que el vehículo tiene una diferencia grande en el último km
                $diferencia = $bitacora->km_inicial-$penultimaBitacora->km_final-3;
                event(new NotificarDiferenciaKmToAdmin($bitacora, $this->admin_vehiculos->id, $diferencia));
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function actualizarDatosRelacionadosBitacora(BitacoraVehicular $bitacora, Request $request)
    {
        try {
            foreach ($request->actividadesRealizadas as $actividad) {
                $actualizada = $this->polymorphicGenericService->actualizarActividadPolimorfica($bitacora, $actividad);
                if (!$actualizada) $this->polymorphicGenericService->crearActividadPolimorfica($bitacora, $actividad);
            }
            $this->actualizarChecklistAccesoriosVehiculo($bitacora->id, $request->checklistAccesoriosVehiculo);
            $this->actualizarChecklistVehiculo($bitacora->id, $request->checklistVehiculo);
            $this->actualizarChecklistImagenesVehiculo($bitacora->id, $request->checklistImagenVehiculo);

            $this->notificarNovedadesVehiculo($bitacora);
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['error en actualizarDatosRelacionadosBitacora', $th->getLine()]);
            throw $th;
        }
    }

    private function actualizarChecklistAccesoriosVehiculo(int $bitacora_id, array $datos)
    {
        $checklist = ChecklistAccesoriosVehiculo::where('bitacora_id', $bitacora_id)->first();
        if ($checklist) {
            $checklist->update($datos);
        } else {
            $datos['bitacora_id'] = $bitacora_id;
            ChecklistAccesoriosVehiculo::create($datos);
        }
    }

    private function actualizarChecklistVehiculo(int $bitacora_id, array $datos)
    {
        $checklist = ChecklistVehiculo::where('bitacora_id', $bitacora_id)->first();
        if ($checklist) {
            $checklist->update($datos);
        } else {
            $datos['bitacora_id'] = $bitacora_id;
            ChecklistVehiculo::create($datos);
        }
    }

    /**
     * @throws Throwable
     */
    private function actualizarChecklistImagenesVehiculo(int $bitacora_id, array $datos)
    {
        $checklist = ChecklistImagenVehiculo::where('bitacora_id', $bitacora_id)->first();
        if ($checklist) {
            //se modifica el registro obtenido, imagenes que hayan cambiado o nuevas y asi mismo en la base de datos
            if ($datos['imagen_frontal'] && Utils::esBase64($datos['imagen_frontal'])) {
                $datos['imagen_frontal'] = (new GuardarImagenIndividual($datos['imagen_frontal'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_frontal']);
            }
            if ($datos['imagen_trasera'] && Utils::esBase64($datos['imagen_trasera'])) {
                $datos['imagen_trasera'] = (new GuardarImagenIndividual($datos['imagen_trasera'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_trasera']);
            }
            if ($datos['imagen_lateral_derecha'] && Utils::esBase64($datos['imagen_lateral_derecha'])) {
                $datos['imagen_lateral_derecha'] = (new GuardarImagenIndividual($datos['imagen_lateral_derecha'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_lateral_derecha']);
            }
            if ($datos['imagen_lateral_izquierda'] && Utils::esBase64($datos['imagen_lateral_izquierda'])) {
                $datos['imagen_lateral_izquierda'] = (new GuardarImagenIndividual($datos['imagen_lateral_izquierda'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_lateral_izquierda']);
            }
            if ($datos['imagen_tablero_km'] && Utils::esBase64($datos['imagen_tablero_km'])) {
                $datos['imagen_tablero_km'] = (new GuardarImagenIndividual($datos['imagen_tablero_km'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_tablero_km']);
            }
            if ($datos['imagen_tablero_radio'] && Utils::esBase64($datos['imagen_tablero_radio'])) {
                $datos['imagen_tablero_radio'] = (new GuardarImagenIndividual($datos['imagen_tablero_radio'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_tablero_radio']);
            }
            if ($datos['imagen_asientos'] && Utils::esBase64($datos['imagen_asientos'])) {
                $datos['imagen_asientos'] = (new GuardarImagenIndividual($datos['imagen_asientos'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_asientos']);
            }
            if ($datos['imagen_accesorios'] && Utils::esBase64($datos['imagen_accesorios'])) {
                $datos['imagen_accesorios'] = (new GuardarImagenIndividual($datos['imagen_accesorios'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                unset($datos['imagen_accesorios']);
            }
            if (is_null($datos['observacion']) || strlen($datos['observacion']) <= 1) $datos['observacion'] = 'NINGUNA';
            $checklist->update($datos);
        } else {
            //se guarda el registro, primero las imagenes en el servidor y luego las url en la base de datos
            if ($datos['imagen_frontal'] && Utils::esBase64($datos['imagen_frontal'])) {
                $datos['imagen_frontal'] = (new GuardarImagenIndividual($datos['imagen_frontal'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_frontal'] = null;
            }
            if ($datos['imagen_trasera'] && Utils::esBase64($datos['imagen_trasera'])) {
                $datos['imagen_trasera'] = (new GuardarImagenIndividual($datos['imagen_trasera'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_trasera'] = null;
            }
            if ($datos['imagen_lateral_derecha'] && Utils::esBase64($datos['imagen_lateral_derecha'])) {
                $datos['imagen_lateral_derecha'] = (new GuardarImagenIndividual($datos['imagen_lateral_derecha'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_lateral_derecha'] = null;
            }
            if ($datos['imagen_lateral_izquierda'] && Utils::esBase64($datos['imagen_lateral_izquierda'])) {
                $datos['imagen_lateral_izquierda'] = (new GuardarImagenIndividual($datos['imagen_lateral_izquierda'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_lateral_izquierda'] = null;
            }
            if ($datos['imagen_tablero_km'] && Utils::esBase64($datos['imagen_tablero_km'])) {
                $datos['imagen_tablero_km'] = (new GuardarImagenIndividual($datos['imagen_tablero_km'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_tablero_km'] = null;
            }
            if ($datos['imagen_tablero_radio'] && Utils::esBase64($datos['imagen_tablero_radio'])) {
                $datos['imagen_tablero_radio'] = (new GuardarImagenIndividual($datos['imagen_tablero_radio'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_tablero_radio'] = null;
            }
            if ($datos['imagen_asientos'] && Utils::esBase64($datos['imagen_asientos'])) {
                $datos['imagen_asientos'] = (new GuardarImagenIndividual($datos['imagen_asientos'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_asientos'] = null;
            }
            if ($datos['imagen_accesorios'] && Utils::esBase64($datos['imagen_accesorios'])) {
                $datos['imagen_accesorios'] = (new GuardarImagenIndividual($datos['imagen_accesorios'], RutasStorage::FOTOGRAFIAS_DIARIAS_VEHICULOS))->execute();
            } else {
                $datos['imagen_accesorios'] = null;
            }

            $datos['bitacora_id'] = $bitacora_id;
            if (is_null($datos['observacion']) || strlen($datos['observacion']) <= 1) $datos['observacion'] = 'NINGUNA';
            ChecklistImagenVehiculo::create($datos);
        }
    }

    /**
     * @throws Throwable
     */
    public function notificarNovedadesVehiculo($bitacora)
    {
        try {
            //code...
            if ($bitacora->firmada) {
                //Lanzar notificacion de advertencia de combustible
                if ($bitacora->tanque_final < 50) {
                    event(new NotificarBajoNivelCombustible($bitacora, $this->admin_vehiculos->id));
                }

                //Aquí se revisa si hay algún elemento con problemas y se envía un resumen
                // por notificación con los problemas del vehículo
                $advertenciasEncontradas = $this->resumenElementosBitacora($bitacora);
                if (empty($advertenciasEncontradas))  Log::channel('testing')->info('Log', ['No se encontraron advertencias ']);
                else {
                    Log::channel('testing')->info('Log', ['Si se encontraron advertencias', $advertenciasEncontradas]);
                    //Aquí se debe notificar por notificacion y correo
                    event(new NotificarAdvertenciasVehiculoBitacora($bitacora, $advertenciasEncontradas, $this->admin_vehiculos->id));
                }

                $this->verificarMantenimientosPlanMantenimientos($bitacora);
            }
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en notificarNovedadesVehiculo', $th->getLine()]);
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    private function verificarMantenimientosPlanMantenimientos(BitacoraVehicular $bitacora)
    {
        try {
            //obtenemos los mantenimientos según el plan de mantenimiento asociado al vehículo
            Log::channel('testing')->info('Log', ['bitacora vehículo', $bitacora->vehiculo]);
            $itemsMantenimiento = $bitacora->vehiculo->itemsMantenimiento()->where('activo', true)->get();
            Log::channel('testing')->info('Log', ['Items de mantenimiento del vehículo', $itemsMantenimiento]);
            Log::channel('testing')->info('Log', ['IDS Items de mantenimiento', $itemsMantenimiento->pluck('servicio_id')]);
            // Verificamos si ya ha habido mantenimientos anteriores para comprobar el más reciente
            $mantenimientosRealizados = MantenimientoVehiculo::where('vehiculo_id', $bitacora->vehiculo->id)
                ->whereIn('servicio_id', $itemsMantenimiento->pluck('servicio_id'))->orderBy('id', 'desc')->get();
            $mantenimientosRealizados = $mantenimientosRealizados->unique('servicio_id');
            Log::channel('testing')->info('Log', ['Planes de Mantenimientos realizados al vehiculo', $bitacora->vehiculo->placa, $itemsMantenimiento]);
            Log::channel('testing')->info('Log', ['Mantenimientos realizados al vehiculo', $bitacora->vehiculo->placa, $mantenimientosRealizados]);
            if ($mantenimientosRealizados->count() < 1) {
                Log::channel('testing')->info('Log', ['No han habido mantenimientos previos']);
                //Se verifica si ya es hora de notificar o de hacerse el mantenimiento
                foreach ($itemsMantenimiento as $item) {
                    //                                       1000        +   5000              -      500 = 5500
                    if ($bitacora->km_final >= ($item->aplicar_desde + $item->aplicar_cada - $item->notificar_antes)) {
                        // Se crea mantenimiento según el plan de mantenimientos y se notifica al admin
                        $nuevoMantenimiento = $this->crearMantenimiento($bitacora->vehiculo->id, $item['servicio_id']);
                        event(new NotificarMantenimientoCreado($nuevoMantenimiento));
                        Log::channel('testing')->info('Log', ['Se creó nuevo mantenimiento', $nuevoMantenimiento]);
                    }
                }
            } else {
                // recorremos cada mantenimiento para trabajar con la fecha de realizado y el km realizado
                foreach ($mantenimientosRealizados as $mantenimiento) {
                    $itemMantenimiento =  $this->mantenimientoService->obtenerItemMantenimiento($itemsMantenimiento, $mantenimiento['vehiculo_id'], $mantenimiento['servicio_id']);
                    Log::channel('testing')->info('Log', ['238 $item', $itemMantenimiento, $mantenimiento]);
                    if ($itemMantenimiento)
                        //Suponiendo que el cambio de aceite se hizo al km 5682
                        // sumamos 5600 + $5000 - $500 = 10100 km
                        // 10112 >= 10100 = true
                        // R= A los 10182 km debe crearse nuevamente la alerta de mantenimiento del vehículo.
                        if ($bitacora->km_final >= ($mantenimiento['km_realizado'] + $itemMantenimiento['aplicar_cada'] - $itemMantenimiento['notificar_antes'])) {
                            // Verificamos si ya hay un mantenimiento creado y está con estado PENDIENTE, en ese caso solo se notifica al admin de vehiculos
                            if ($mantenimiento['estado'] === MantenimientoVehiculo::PENDIENTE || $mantenimiento['estado'] === MantenimientoVehiculo::RETRASADO) {
                                // Lanzar notificacion al admin de vehiculos
                                Log::channel('testing')->info('Log', ['El mantenimiento esta pendiente', $mantenimiento]);
                                $mantenimiento->latestNotificacion()->update(['leida' => true]);
                                $this->mantenimientoService->actualizarMantenimiento($bitacora, $itemMantenimiento, $mantenimiento);
                                event(new NotificarMantenimientoPendienteRetrasadoEvent($mantenimiento));
                            } else {
                                // Se crea el mantenimiento nuevo que toca en este momento.
                                if ($mantenimiento['estado'] === MantenimientoVehiculo::REALIZADO) {
                                    $nuevoMantenimiento = $this->crearMantenimiento($bitacora->vehiculo->id, $mantenimiento['servicio_id']);
                                    event(new NotificarMantenimientoCreado($nuevoMantenimiento));
                                    Log::channel('testing')->info('Log', ['Se creó nuevo mantenimiento en el else', $nuevoMantenimiento]);
                                } else Log::channel('testing')->info('Log', ['El mantenimiento no entró en ningun estado, es', $mantenimiento]);
                            }
                        }
                }
            }
        } catch (Throwable $th) {
            Log::channel('testing')->info('Log', ['Error en verificarMantenimientosPlanMantenimientos', $th->getLine()]);
            throw $th;
        }
    }

    /**
     * La función crea un nuevo registro de mantenimiento para un vehículo con servicio específico y lo
     * asigna a un empleado y supervisor.
     *
     * @param int $vehiculo_id El parámetro `vehiculo_id` es un número entero que representa el ID del
     * vehículo para el cual se está creando el mantenimiento.
     * @param int $servicio_id El parámetro `servicio_id` en la función `crearMantenimiento` representa
     * el ID del servicio que se está asignando a la tarea de mantenimiento de un vehículo. Este ID se
     * utiliza para asociar un servicio específico con el registro de mantenimiento en la base de
     * datos.
     *
     * @return MantenimientoVehiculo La función `crearMantenimiento` devuelve el objeto `MantenimientoVehiculo` recién creado
     * si la creación es exitosa. Si ocurre un error durante el proceso de creación, se generará una
     * excepción.
     * @throws Throwable
     */
    private function crearMantenimiento(int $vehiculo_id, int  $servicio_id)
    {
        try {
            DB::beginTransaction();
            $nuevoMantenimiento = MantenimientoVehiculo::create([
                'vehiculo_id' => $vehiculo_id,
                'servicio_id' => $servicio_id,
                'empleado_id' => auth()->user()->empleado->id,
                'supervisor_id' => $this->admin_vehiculos->id,
            ]);
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return $nuevoMantenimiento;
    }

    private function resumenElementosBitacora($bitacora)
    {
        // Definimos los valores de cada clave a sumar
        $results = [];
        $results['correcto'] = 0;
        $results['advertencia'] = 0;
        $results['peligro'] = 0;
        $results['lleno'] = 0;
        $results['vacio'] = 0;
        $results['caducado'] = 0;
        $results['bueno'] = 0;
        $results['malo'] = 0;

        //Contamos los elementos categorizados para saber cuantos hay de cada uno
        $resultsAccesorios = $this->contarElementos($results, $bitacora->checklistAccesoriosVehiculo);
        $resultsVehiculo = $this->contarElementos($results, $bitacora->checklistVehiculo);

        // Sumamos ambos arrays para obtener un array con la suma de cada clave
        $results = $this->sumarArray($resultsAccesorios, $resultsVehiculo);


        //Filtramos los resultados para obtener solo los elementos diferentes a cero
        $results = array_filter($results, function ($value) {
            return $value !== 0;
        });
        Log::channel('testing')->info('Log', ['Results sin ceros', $results]);

        $claves_a_comprobar = ["advertencia", "peligro", "vacio", "caducado", "malo"];

        return array_intersect_key($results, array_flip($claves_a_comprobar));
    }

    /**
     * La función cuenta las apariciones de atributos específicos en un objeto modelo y actualiza una
     * matriz de resultados en consecuencia.
     *
     * @param array $results Un array al que se le van a sumar de los valores de los atributos encontrados.
     * @param Model $model El modelo al que se le van a contar los atributos.
     *
     * @return array la matriz `$results` actualizada después de contar las apariciones de diferentes
     * atributos en el objeto modelo `$data`.
     */
    private function contarElementos(array $results, Model $model)
    {
        foreach ($model->getAttributes() as $key => $value) {
            if (preg_match('/observacion/i', $key)) continue;
            if ($value === BitacoraVehicular::CORRECTO) $results['correcto']++;
            if ($value === BitacoraVehicular::ADVERTENCIA) $results['advertencia']++;
            if ($value === BitacoraVehicular::PELIGRO) $results['peligro']++;
            if ($value === BitacoraVehicular::LLENO) $results['lleno']++;
            if ($value === BitacoraVehicular::VACIO) $results['vacio']++;
            if ($value === BitacoraVehicular::CADUCADO) $results['caducado']++;
            if ($value === BitacoraVehicular::BUENO) $results['bueno']++;
            if ($value === BitacoraVehicular::MALO) $results['malo']++;
        }

        return $results;
    }

    /**
     * La función `sumarArray` toma dos matrices como entrada y devuelve una nueva matriz con la suma
     * de los elementos correspondientes de las matrices de entrada.
     *
     * @param array $array1 La matriz 1 es una matriz asociativa que contiene claves y valores.
     * @param array $array2 La matriz 2 que contiene los mismos claves y valores de la matriz 1.
     *
     * @return array Devuelve una matriz que contiene la suma de los elementos correspondientes
     * de ambas matrices.
     */
    private function sumarArray(array $array1, array $array2)
    {
        $results = [];
        foreach ($array1 as $key => $value) {
            $results[$key] = $value + $array2[$key];
        }
        return $results;
    }


    /**
     * @throws Exception
     */
    public function generarPdf(BitacoraVehicular $bitacora)
    {
        try {
            $bita = $bitacora;
            $configuracion = ConfiguracionGeneral::first();
            $modelo = new BitacoraVehicularResource($bitacora);
            $vehiculo = new VehiculoResource(Vehiculo::find($bitacora->vehiculo_id));
            $chofer = Empleado::find($bitacora->chofer_id);
            //aplanar collection
            $bitacora = $modelo->resolve();
            $bitacora['actividadesRealizadas'] = $bita->actividades;
            $bitacora['checklistAccesoriosVehiculo'] = $bita->checklistAccesoriosVehiculo;
            $bitacora['checklistVehiculo'] = $bita->checklistVehiculo;
            $bitacora['checklistImagenVehiculo'] = $bita->checklistImagenVehiculo;
            $vehiculo = $vehiculo->resolve();
            // Log::channel('testing')->info('Log', ['Datos que se pasan a la plantilla', compact(['bitacora', 'vehiculo', 'chofer', 'configuracion'])]);
            $pdf = Pdf::loadView('vehiculos.bitacora_vehicular', compact(['bitacora', 'vehiculo', 'chofer', 'configuracion']));
            $pdf->setPaper('A4');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            //se genera el pdf

            return $pdf->output();
        } catch (Throwable $th) {
            throw new Exception(Utils::obtenerMensajeError($th, 'No se pudo generar el PDF..'), 1, $th);
        }
    }
}
