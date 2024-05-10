<?php

namespace Src\App\Vehiculos;

use App\Events\Vehiculos\NotificarAdvertenciasVehiculoBitacora;
use App\Events\Vehiculos\NotificarBajoNivelCombustible;
use App\Http\Resources\Vehiculos\BitacoraVehicularResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\ChecklistAccesoriosVehiculo;
use App\Models\Vehiculos\ChecklistImagenVehiculo;
use App\Models\Vehiculos\ChecklistVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;
use Src\App\EmpleadoService;
use Src\App\PolymorphicGenericService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class BitacoraVehicularService
{

    private $polymorphicGenericService;
    public function __construct()
    {
        $this->polymorphicGenericService = new PolymorphicGenericService();
    }

    public function guardarDatosRelacionadosBitacora()
    {
    }

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
        } catch (\Throwable $th) {
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
            ChecklistImagenVehiculo::create($datos);
        }
    }

    public function notificarNovedadesVehiculo($bitacora)
    {
        Log::channel('testing')->info('Log', ['updated del notificarNovedadesVehiculo']);
        if ($bitacora->firmada) {
            //Lanzar notificacion de advertencia de combustible
            if ($bitacora->tanque_final < 50) {
                $admin_vehiculos = EmpleadoService::obtenerEmpleadoRolEspecifico(User::ROL_ADMINISTRADOR_VEHICULOS, true);
                event(new NotificarBajoNivelCombustible($bitacora, $admin_vehiculos->id));
            }

            //Aquí se revisa si hay algun elemento con problemas y se envía un resumen 
            // por notificación con los problemas del vehiculo
            $advertenciasEncontradas = $this->resumenElementosBitacora($bitacora);
            if (empty($advertenciasEncontradas))  Log::channel('testing')->info('Log', ['No se encontraron advertencias ']);
            else {
                Log::channel('testing')->info('Log', ['Si se encontraron advertencias', $advertenciasEncontradas]);
                //Aquí se debe notificar por notificacion y correo
                event(new NotificarAdvertenciasVehiculoBitacora($bitacora, $advertenciasEncontradas, $admin_vehiculos->id));
            }

            $this->verificarMantenimientosPlanMantenimientos($bitacora);
        }
    }

    private function verificarMantenimientosPlanMantenimientos(BitacoraVehicular $bitacora)
    {
        //obtenemos los mantenimientos según el plan de mantenimiento asociado al vehiculo
        Log::channel('testing')->info('Log', ['bitacora vehículo', $bitacora->vehiculo]);
        $itemsMantenimiento = $bitacora->vehiculo->itemsMantenimiento()->where('activo', true)->get();
        Log::channel('testing')->info('Log', ['Items de mantenimiento del vehículo', $itemsMantenimiento]);

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

        $clavesEncontradas = array_intersect_key($results, array_flip($claves_a_comprobar));

        return $clavesEncontradas;
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


    public function generarPdf(BitacoraVehicular $bitacora, $descargar = true)
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
            Log::channel('testing')->info('Log', ['Datos que se pasan a la plantilla', compact(['bitacora', 'vehiculo', 'chofer', 'configuracion'])]);
            $pdf = Pdf::loadView('vehiculos.bitacora_vehicular', compact(['bitacora', 'vehiculo', 'chofer', 'configuracion']));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();
            $file = $pdf->output(); //se genera el pdf

            return $file;
        } catch (\Throwable $th) {
            throw new \Exception(Utils::obtenerMensajeError($th, 'No se pudo generar el PDF..'), 1, $th);
        }
    }
}
