<?php

namespace Src\App\FondosRotativos;

use App\Http\Requests\GastoRequest;
use App\Http\Resources\FondosRotativos\Gastos\GastoVehiculoResource;
use App\Models\FondosRotativos\Gasto\BeneficiarioGasto;
use App\Models\FondosRotativos\Gasto\Gasto;
use App\Models\FondosRotativos\Gasto\GastoVehiculo;
use App\Models\FondosRotativos\Gasto\SubdetalleGasto;
use App\Models\FondosRotativos\Valija;
use App\Models\Notificacion;
use App\Models\Vehiculos\Vehiculo;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class GastoService
{
    private Gasto $gasto;
    private string $entidad = 'gasto';

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
     * @param GastoRequest $request es un objeto de tipo GastoRequest que contiene detalles de un
     * gasto que necesita ser validado para un gasto de vehículo. Probablemente incluya información como el
     * detalle y sub_detalle del gasto.
     * @throws ValidationException|Throwable
     */
    public function validarGastoVehiculo(GastoRequest $request)
    {
        $gasto_vehiculo = GastoVehiculo::where('id_gasto', $this->gasto->id)->first();

        // Caso detalle == 24
        if ($request->detalle == 24) {
            is_null($gasto_vehiculo)
                ? $this->guardarGastoVehiculo($request, $this->gasto)
                : $this->modificarGastovehiculo($request, $this->gasto);
        }

        // Caso detalle == 6 o 16 con sub_detalles específicos
        if ($request->detalle == 6 || $request->detalle == 16) {
            $sub_detalle = array_flip(array_map('intval', $request->sub_detalle ?? []));
            $keys_interes = [65, 66, 96, 97];

            // Verificar si alguno de los sub_detalle está presente
            $tiene_clave = count(array_intersect_key(array_flip($keys_interes), $sub_detalle)) > 0;

            if ($tiene_clave) {
                is_null($gasto_vehiculo)
                    ? $this->guardarGastoVehiculo($request, $this->gasto)
                    : $this->modificarGastovehiculo($request, $this->gasto);
            }
        }
    }

    /**
     * @throws Throwable
     */
    public function guardarRegistrosValijas(array $datos, array $envio_valija)
    {
        //        Log::channel('testing')->info('Log', ['guardarRegistrosValijas', $envio_valija]);
        if ($envio_valija['fotografia_guia']) {
            $envio_valija['fotografia_guia'] = (new GuardarImagenIndividual($envio_valija['fotografia_guia'], RutasStorage::IMAGENES_VALIJAS))->execute();
        }

        $envioValija = $this->gasto->envioValija()->create([
            'empleado_id' => $this->gasto->id_usuario,
            'courier' => $envio_valija['courier'],
            'fotografia_guia' => $envio_valija['fotografia_guia'],
        ]);

        //        Log::channel('testing')->info('Log', ['Envio Valija Creado', $envioValija]);

        //        throw new Exception(Utils::metodoNoDesarrollado());
        try {
            DB::beginTransaction();

            foreach ($datos as $dato) {
                $dato['envio_valija_id'] = $envioValija->id;
                if ($dato['imagen_evidencia']) {
                    $dato['imagen_evidencia'] = (new GuardarImagenIndividual($dato['imagen_evidencia'], RutasStorage::IMAGENES_VALIJAS))->execute();
                }
                Valija::create($dato);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['error', $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public static function convertirComprobantesBase64Url(array $datos, $tipo_metodo = 'store', Gasto $gasto = null)
    {
        switch ($tipo_metodo) {
            case 'store':
                if ($datos['comprobante']) {
                    $datos['comprobante'] = (new GuardarImagenIndividual($datos['comprobante'], RutasStorage::COMPROBANTES_GASTOS))->execute();
                }
                if ($datos['comprobante2']) {
                    $datos['comprobante2'] = (new GuardarImagenIndividual($datos['comprobante2'], RutasStorage::COMPROBANTES_GASTOS))->execute();
                }
                if (isset($datos['comprobante3']))
                    $datos['comprobante3'] = (new GuardarImagenIndividual($datos['comprobante3'], RutasStorage::COMPROBANTES_GASTOS))->execute();
                if (isset($datos['comprobante4']))
                    $datos['comprobante4'] = (new GuardarImagenIndividual($datos['comprobante4'], RutasStorage::COMPROBANTES_GASTOS))->execute();
                break;
            case 'update':
                if ($datos['comprobante'] && Utils::esBase64($datos['comprobante'])) {
                    $datos['comprobante'] = (new GuardarImagenIndividual($datos['comprobante'], RutasStorage::COMPROBANTES_GASTOS, $gasto?->comprobante))->execute();
                } else {
                    unset($datos['comprobante']);
                }
                if ($datos['comprobante2'] && Utils::esBase64($datos['comprobante2'])) {
                    $datos['comprobante2'] = (new GuardarImagenIndividual($datos['comprobante2'], RutasStorage::COMPROBANTES_GASTOS, $gasto?->comprobante2))->execute();
                } else {
                    unset($datos['comprobante2']);
                }
                if (isset($datos['comprobante3']) && Utils::esBase64($datos['comprobante3']))
                    $datos['comprobante3'] = (new GuardarImagenIndividual($datos['comprobante3'], RutasStorage::COMPROBANTES_GASTOS, $gasto?->comprobante3))->execute();
                else unset($datos['comprobante3']);

                if (isset($datos['comprobante4']) && Utils::esBase64($datos['comprobante4']))
                    $datos['comprobante4'] = (new GuardarImagenIndividual($datos['comprobante4'], RutasStorage::COMPROBANTES_GASTOS, $gasto?->comprobante4))->execute();
                else unset($datos['comprobante4']);
                break;
        }
        return $datos;
    }

    /**
     * La función `crearBeneficiarios` crea nuevas entradas de beneficiarios para un gasto determinado.
     *
     * @param Collection|array|null $beneficiarios La función `createBeneficiaries` toma un objeto `Expense` y una matriz de
     * `beneficiarios` como parámetros. La matriz `beneficiarios` contiene valores `employee_id` que
     * representan los ID de los empleados que se asociarán con el objeto `Expense` dado.
     */
    public function crearBeneficiarios(Collection|array|null $beneficiarios)
    {
        if (empty($beneficiarios)) return;

        foreach ($beneficiarios as $empleado_id) {
            BeneficiarioGasto::insert([
                'gasto_id' => $this->gasto->id,
                'empleado_id' => $empleado_id
            ]);
        }
    }

    public function crearSubDetalle($subdetalles)
    {
        if ($subdetalles != null) {
            foreach ($subdetalles as $subdetalle_gasto_id) {
                SubdetalleGasto::create([
                    'gasto_id' => $this->gasto->id,
                    'subdetalle_gasto_id' => $subdetalle_gasto_id
                ]);
            }
        }
    }

    public function sincronizarSubDetalle($sub_detalle)
    {
        // Supongamos que $ids es tu arreglo de empleados _id
        $ids = $sub_detalle;
        // Obtener el gasto al que se asocian los sub_de$sub_detalle
        $gasto_id = $this->gasto->id;
        // Obtener los registros existentes de sub_de$sub_detalle para este gasto
        $registrosExistentes = SubdetalleGasto::where('gasto_id', $gasto_id)->pluck('subdetalle_gasto_id');
        // Filtrar los ids para evitar repeticiones
        $idsNuevos = collect($ids)->diff($registrosExistentes);
        // Insertar los nuevos registros
        foreach ($idsNuevos as $subdetalle_gasto_id) {
            SubdetalleGasto::create([
                'gasto_id' => $gasto_id,
                'subdetalle_gasto_id' => $subdetalle_gasto_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Eliminar los registros que ya no están en el arreglo $ids
        $registrosEliminar = $registrosExistentes->diff($ids);
        SubdetalleGasto::where('gasto_id', $gasto_id)->whereIn('subdetalle_gasto_id', $registrosEliminar)->delete();
    }

    public function sincronizarBeneficiarios($beneficiarios)
    {
        // Supongamos que $ids es tu arreglo de empleados _id
        $ids = $beneficiarios;
        // Obtener el gasto al que se asocian los beneficiarios
        $gasto_id = $this->gasto->id;
        // Obtener los registros existentes de beneficiarios para este gasto
        $registrosExistentes = BeneficiarioGasto::where('gasto_id', $gasto_id)->pluck('empleado_id');
        // Filtrar los ids para evitar repeticiones
        $idsNuevos = collect($ids)->diff($registrosExistentes);
        // Insertar los nuevos registros
        foreach ($idsNuevos as $empleado_id) {
            BeneficiarioGasto::create([
                'gasto_id' => $gasto_id,
                'empleado_id' => $empleado_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Eliminar los registros que ya no están en el arreglo $ids
        $registrosEliminar = $registrosExistentes->diff($ids);
        BeneficiarioGasto::where('gasto_id', $gasto_id)->whereIn('empleado_id', $registrosEliminar)->delete();
    }

    /**
     * La función `guardarGastoVehiculo` ahorra gasto de vehículo con validación y manejo de errores en PHP
     * usando Laravel.
     *
     * @param GastoRequest $request La función `guardarGastoVehiculo` se encarga de guardar un registro de
     * gastos del vehículo en base a los objetos `GastoRequest` y `Gasto` proporcionados. Analicemos el
     * fragmento de código:
     * @param Gasto $gasto Con base en el fragmento de código proporcionado, la función
     * `guardarGastoVehiculo` es responsable de ahorrar un gasto de vehículo (“GastoVehiculo`) asociado a
     * un `Gasto` específico. La función toma dos parámetros:
     *
     * @return JsonResponse función `guardarGastoVehiculo` está devolviendo una respuesta JSON con las variables
     * `` y `` compactadas. La variable `` contiene un mensaje obtenido usando el
     * método `Utils::obtenerMensaje` para la entidad y acción 'store'. La variable `` contiene la
     * representación de recursos del objeto `GastoVehiculo` creado.
     * @throws ValidationException
     * @throws Throwable
     */
    public function guardarGastoVehiculo(GastoRequest $request, Gasto $gasto)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['id_gasto'] = $gasto->id;
            $datos['id_vehiculo'] = $request->vehiculo == 0 ? null : $request->safe()->only(['vehiculo'])['vehiculo'];
            $datos['placa'] = $request->es_vehiculo_alquilado ? $request->placa : Vehiculo::where('id', $datos['id_vehiculo'])->first()->placa;
            $datos['es_vehiculo_alquilado'] = $request->es_vehiculo_alquilado;
            $gasto_vehiculo = GastoVehiculo::create($datos);
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
     * @param GastoRequest $request La función `modificarGastoVehiculo` parece estar actualizando un
     * registro en la tabla `gasto_vehiculos` en base a los datos proporcionados en el objeto
     * `GastoRequest` y el objeto `Gasto` existente.
     * @param Gasto $gasto La función `modificarGastoVehiculo` se utiliza para actualizar un registro
     * específico de `GastoVehiculo` basado en los datos de `GastoRequest` proporcionados y la instancia de
     * `Gasto` existente.
     *
     * @return JsonResponse función `modificarGastoVehiculo` está devolviendo una respuesta JSON con las variables
     * `mensaje` y `modelo`. La variable `mensaje` contiene un mensaje obtenido usando el método
     * `Utils::obtenerMensaje` para la acción 'almacenar' sobre la entidad. La variable `modelo` contiene
     * los datos del recurso `GastoVehiculo` actualizado luego de la modificación.
     * @throws Throwable
     */
    public function modificarGastoVehiculo(GastoRequest $request, Gasto $gasto)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['id_gasto'] = $request->id;
            $datos['id_vehiculo'] = $request->vehiculo == 0 ? null : $request->safe()->only(['vehiculo'])['vehiculo'];
            $datos['placa'] = $request->es_vehiculo_alquilado ? $request->placa : Vehiculo::where('id', $datos['id_vehiculo'])->first()->placa;
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

    /**
     * Valida que un gasto no se encuentre aprobado o rechazado para evitar modificaciones.
     * Con esto se limita que un gasto pueda ser modificado una vez que ha sido aprobado, anulado o rechazado.
     * @param Gasto $gasto
     * @return void
     * @throws Exception
     */
    public static function validarNoAprobado(Gasto $gasto)
    {
        if ($gasto->estado !== Gasto::PENDIENTE) {
            $estado = $gasto->estadoViatico->descripcion;
            throw new Exception("El gasto no se puede modificar porque se encuentra con estado $estado");
        }
    }
}
