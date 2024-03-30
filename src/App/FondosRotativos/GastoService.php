<?php

namespace Src\App\FondosRotativos;

use App\Http\Requests\GastoRequest;
use App\Http\Resources\FondosRotativos\Gastos\GastoVehiculoResource;
use App\Models\FondosRotativos\Gasto\BeneficiarioGasto;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\GastoVehiculo;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\Notificacion;
use App\Models\Vehiculos\Vehiculo;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class GastoService
{
    private $gasto;
    private $entidad = 'gasto';

    public function __construct(Gasto $gasto)
    {
        $this->gasto = $gasto;
    }
    public function marcarNotificacionLeida()
    {
        $notificacion_remitente = Notificacion::where('per_originador_id', $this->gasto->id_usuario)
            ->where('per_destinatario_id', $this->gasto->aut_especial)
            ->where('tipo_notificacion', 'AUTORIZACION GASTO')
            ->where('leida', 0)
            ->where('notificable_id', $this->gasto->id)
            ->first();
        if ($notificacion_remitente) {
            $notificacion_remitente->leida = 1;
            $notificacion_remitente->save();
        }
    }

    /**
     * La función `validarGastoVehiculo` verifica ciertas condiciones en una solicitud y guarda o modifica
     * un objeto GastoVehiculo en consecuencia.
     *
     * @param GastoRequest request  es un objeto de tipo GastoRequest que contiene detalles de un
     * gasto que necesita ser validado para un gasto de vehículo. Probablemente incluya información como el
     * detalle y sub_detalle del gasto.
     * @param Gasto gasto Según el fragmento de código proporcionado, la función `validarGastoVehiculo`
     * toma dos parámetros: `` de tipo `GastoRequest` y `` de tipo `Gasto`. La función
     * verifica el valor de `->detalle` y, según ciertas condiciones,
     */
    public function validarGastoVehiculo(GastoRequest $request)
    {
        $gasto_vehiculo = GastoVehiculo::where('id_gasto', $this->gasto->id)->first();
        if ($request->detalle == 24) {
            is_null($gasto_vehiculo) ? $this->guardarGastoVehiculo($request, $this->gasto) : $this->modificarGastovehiculo($request, $this->gasto);
        }
        if ($request->detalle == 6 || $request->detalle == 16) {
            $sub_detalle = $request->sub_detalle;
            $sub_detalle = array_map('intval', $sub_detalle);
            $sub_detalle = array_flip($sub_detalle);
            if (array_key_exists(65, $sub_detalle)) {
                is_null($gasto_vehiculo) ? $this->guardarGastoVehiculo($request, $this->gasto) : $this->modificarGastovehiculo($request, $this->gasto);
            }
            if (array_key_exists(66, $sub_detalle)) {
                is_null($gasto_vehiculo) ? $this->guardarGastoVehiculo($request, $this->gasto) : $this->modificarGastovehiculo($request, $this->gasto);
            }
            if (array_key_exists(96, $sub_detalle)) {
                is_null($gasto_vehiculo) ? $this->guardarGastoVehiculo($request, $this->gasto) : $this->modificarGastovehiculo($request, $this->gasto);
            }
            if (array_key_exists(97, $sub_detalle)) {
                is_null($gasto_vehiculo) ? $this->guardarGastoVehiculo($request, $this->gasto) : $this->modificarGastovehiculo($request, $this->gasto);
            }
        }
    }
    public static function  convertirComprobantesBase64Url(array $datos,$comprobante1,$comprobante2 ,$tipo_metodo = 'store')
    {
        switch ($tipo_metodo) {
            case 'store':
                if ($comprobante1) {
                    $datos['comprobante'] = (new GuardarImagenIndividual($comprobante1, RutasStorage::COMPROBANTES_GASTOS))->execute();
                }
                if ($datos['comprobante2']) {
                    $datos['comprobante2'] = (new GuardarImagenIndividual($comprobante2, RutasStorage::COMPROBANTES_GASTOS))->execute();
                }
                break;
            case 'update':
                Log::channel('testing')->info('Log', ['metodo update']);

                if ($comprobante1 && Utils::esBase64($comprobante1)) {
                    $datos['comprobante'] = (new GuardarImagenIndividual($comprobante1, RutasStorage::COMPROBANTES_GASTOS))->execute();
                } else {
                    unset($datos['comprobante']);
                }
                if ($comprobante2 && Utils::esBase64($comprobante2)) {
                    $datos['comprobante2'] = (new GuardarImagenIndividual($comprobante2, RutasStorage::COMPROBANTES_GASTOS))->execute();
                } else {
                    unset($datos['comprobante2']);
                }
                break;
        }
        return $datos;
    }
    /**
     * La función `crearBeneficiarios` crea nuevas entradas de beneficiarios para un gasto determinado.
     *
     * @param Gasto gasto El parámetro `gasto` es una instancia de la clase `Gasto`. Parece representar un
     * gasto o costo específico para el cual es necesario crear beneficiarios. El método crearBeneficiarios
     * se encarga de crear beneficiarios para este gasto.
     * @param beneficiarios La función `createBeneficiaries` toma un objeto `Expense` y una matriz de
     * `beneficiarios` como parámetros. La matriz `beneficiarios` contiene valores `employee_id` que
     * representan los ID de los empleados que se asociarán con el objeto `Expense` dado.
     */
    public function crearBeneficiarios($beneficiarios)
    {
        if ($beneficiarios != null) {
            $beneficiariosActualizados = array();
            foreach ($beneficiarios as $empleado_id) {
                $nuevoElemento = array(
                    'gasto_id' =>  $this->gasto->id,
                    'empleado_id' => $empleado_id
                );
                $beneficiariosActualizados[] = $nuevoElemento;
            }
            BeneficiarioGasto::insert($beneficiariosActualizados);
        }
    }
    public function sincronizarBeneficiarios($beneficiarios)
    {
        // Supongamos que $ids es tu arreglo de empleados _id
        $ids = $beneficiarios;
        // Obtener el gasto al que se asocian los beneficiarios
        $gastoId = $this->gasto?->id;
        // Obtener los registros existentes de beneficiarios para este gasto
        $registrosExistentes = BeneficiarioGasto::where('gasto_id', $gastoId)->pluck('empleado_id');
        // Filtrar los ids para evitar repeticiones
        $idsNuevos = collect($ids)->diff($registrosExistentes);
        // Añadir nuevos registros a la tabla beneficiario_gastos
        $nuevosRegistros = $idsNuevos->map(function ($empleadoId) use ($gastoId) {
            return [
                'gasto_id' => $gastoId,
                'empleado_id' => $empleadoId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
        // Insertar los nuevos registros
        BeneficiarioGasto::insert($nuevosRegistros->toArray());
        // Eliminar los registros que ya no están en el arreglo $ids
        $registrosEliminar = $registrosExistentes->diff($ids);
        BeneficiarioGasto::where('gasto_id', $gastoId)->whereIn('empleado_id', $registrosEliminar)->delete();
    }
    /**
     * La función `guardarGastoVehiculo` ahorra gasto de vehículo con validación y manejo de errores en PHP
     * usando Laravel.
     *
     * @param GastoRequest request La función `guardarGastoVehiculo` se encarga de guardar un registro de
     * gastos del vehículo en base a los objetos `GastoRequest` y `Gasto` proporcionados. Analicemos el
     * fragmento de código:
     * @param Gasto gasto Con base en el fragmento de código proporcionado, la función
     * `guardarGastoVehiculo` es responsable de ahorrar un gasto de vehículo (“GastoVehiculo`) asociado a
     * un `Gasto` específico. La función toma dos parámetros:
     *
     * @return La función `guardarGastoVehiculo` está devolviendo una respuesta JSON con las variables
     * `` y `` compactadas. La variable `` contiene un mensaje obtenido usando el
     * método `Utils::obtenerMensaje` para la entidad y acción 'store'. La variable `` contiene la
     * representación de recursos del objeto `GastoVehiculo` creado.
     */
    public function guardarGastoVehiculo(GastoRequest $request, Gasto $gasto)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['id_gasto'] = $gasto->id;
            $datos['id_vehiculo'] = $request->vehiculo == 0 ? null : $request->safe()->only(['vehiculo'])['vehiculo'];
            $datos['placa'] =  $request->es_vehiculo_alquilado ? $request->placa : Vehiculo::where('id', $datos['id_vehiculo'])->first()->placa;
            $datos['es_vehiculo_alquilado'] = $request->es_vehiculo_alquilado;
            $gasto_vehiculo =  GastoVehiculo::create($datos);
            $modelo = new GastoVehiculoResource($gasto_vehiculo);
            DB::table('gasto_vehiculos')->where('id_gasto', '=', $gasto->id)->sharedLock()->get();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage()]]);
        }
    }
    /**
     * Esta función PHP modifica un registro de gastos de vehículo en función de los datos de solicitud
     * proporcionados.
     *
     * @param GastoRequest request La función `modificarGastoVehiculo` parece estar actualizando un
     * registro en la tabla `gasto_vehiculos` en base a los datos proporcionados en el objeto
     * `GastoRequest` y el objeto `Gasto` existente.
     * @param Gasto gasto La función `modificarGastoVehiculo` se utiliza para actualizar un registro
     * específico de `GastoVehiculo` basado en los datos de `GastoRequest` proporcionados y la instancia de
     * `Gasto` existente.
     *
     * @return La función `modificarGastoVehiculo` está devolviendo una respuesta JSON con las variables
     * `mensaje` y `modelo`. La variable `mensaje` contiene un mensaje obtenido usando el método
     * `Utils::obtenerMensaje` para la acción 'almacenar' sobre la entidad. La variable `modelo` contiene
     * los datos del recurso `GastoVehiculo` actualizado luego de la modificación.
     */
    public function modificarGastoVehiculo(GastoRequest $request, Gasto $gasto)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['id_gasto'] = $request->id;
            $datos['id_vehiculo'] = $request->vehiculo == 0 ? null : $request->safe()->only(['vehiculo'])['vehiculo'];
            $datos['placa'] =  $request->es_vehiculo_alquilado ? $request->placa : Vehiculo::where('id', $datos['id_vehiculo'])->first()->placa;
            $datos['es_vehiculo_alquilado'] = $request->es_vehiculo_alquilado;

            $gasto_vehiculo = GastoVehiculo::where('id_gasto', $datos['id_gasto'])->first();
            $gasto_vehiculo->update($datos);
            $modelo = new GastoVehiculoResource($gasto_vehiculo);
            DB::table('gasto_vehiculos')->where('id_gasto', '=', $gasto->id)->sharedLock()->get();
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages(['error' => [$e->getMessage()]]);
        }
    }
}
