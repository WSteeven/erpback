<?php

namespace Src\App\Vehiculos;

use App\Http\Resources\Vehiculos\BitacoraVehicularResource;
use App\Http\Resources\Vehiculos\VehiculoResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Vehiculos\ChecklistAccesoriosVehiculo;
use App\Models\Vehiculos\ChecklistImagenVehiculo;
use App\Models\Vehiculos\ChecklistVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            // Log::channel('testing')->info('Log', ['Datos que se pasan a la plantilla', compact(['bitacora', 'vehiculo', 'chofer', 'configuracion'])]);
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
