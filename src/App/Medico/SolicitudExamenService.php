<?php

namespace Src\App\Medico;

use App\Events\ActualizarNotificacionesEvent;
use App\Events\Medico\CambioFechaHoraSolicitudExamenEvent;
use App\Http\Requests\Medico\SolicitudExamenRequest;
use App\Mail\Medico\CambioFechaHoraSolicitudExamenMail;
use App\Models\Autorizacion;
use App\Models\EstadoTransaccion;
use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\SolicitudExamen;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Src\App\ComprasProveedores\OrdenCompraService;

class SolicitudExamenService
{
    protected $solicitudExamenRequest;
    private OrdenCompraService $ordenCompraService;

    public function __construct() //SolicitudExamenRequest $solicitudExamenRequest)
    {
        $this->solicitudExamenRequest = new SolicitudExamenRequest(); //$solicitudExamenRequest;
        $this->ordenCompraService = new OrdenCompraService();
    }

    public function crearSolicitudExamen(array $data)
    {
        // Si se llama al método desde el controlador, no validar dentro del servicio
        if (!$this->solicitudExamenRequest instanceof Request) { //->isCalledFromController
            $validator = Validator::make($data, $this->solicitudExamenRequest->rules());

            if ($validator->fails()) throw new \Exception($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            Log::channel('testing')->info('Log', ['data', $data]);
            $solicitud = SolicitudExamen::create($data);

            foreach ($data['examenes_solicitados'] as $examenSolicitado) {
                $examen['examen_id'] = $examenSolicitado['examen'];
                $examen['laboratorio_clinico_id'] = $examenSolicitado['laboratorio_clinico'];
                $examen['fecha_hora_asistencia'] = $examenSolicitado['fecha_hora_asistencia'];
                $examen['solicitud_examen_id'] = $solicitud->id;

                EstadoSolicitudExamen::create($examen);
            }

            // Crear orden de compra
            $ordenCompraData = [
                'codigo' => '',
                'solicitante_id' => Auth::user()->empleado->id,
                'proveedor_id' => null,
                'autorizador_id' => $solicitud->autorizador_id,
                'autorizacion_id' => Autorizacion::PENDIENTE_ID, // 'required|numeric|exists:autorizaciones,id',
                'preorden_id' => null, //'nullable|sometimes|numeric|exists:cmp_preordenes_compras,id',
                'pedido_id' => null, // 'nullable|sometimes|numeric|exists:pedidos,id',
                'tarea_id' => null, // 'nullable|sometimes|numeric|exists:tareas,id',
                'observacion_aut' => $solicitud->observacion_autorizador, // 'nullable|sometimes|string',
                'observacion_est' => $solicitud->observacion_autorizador, // 'nullable|sometimes|string',
                'descripcion' => SolicitudExamen::obtenerDescripcionOrdenCompra($solicitud), // 'required|string',
                'forma' => null, //'nullable|string',
                'tiempo' => null, //'nullable|string',
                'fecha' => SolicitudExamen::obtenerFechaMenorExamen($solicitud->examenesSolicitados), // 'required|string',
                'estado_id' => EstadoTransaccion::PENDIENTE_ID, // 'nullable|numeric|exists:estados_transacciones_bodega,id',
                'categorias' => 'SERVICIO', // 'sometimes|nullable',
                'iva' => 12, // 'required|numeric',
            ];

            $listadoProductos = [
                [
                    'id' => 507, //4229,
                    'nombre' => 'SERVICIO',
                    'cantidad' => 1,
                    'descripcion' => SolicitudExamen::obtenerDescripcionDetalleOrdenCompra($solicitud),
                    'precio_unitario' => 0,
                    'facturable' => true,
                    'grava_iva' => false,
                    'iva' => 0,
                    'subtotal' => 0,
                    'total' => 0,
                ],
            ];

            if (request()->boolean('generar_orden_compra')) $this->ordenCompraService->crearOrdenCompra($ordenCompraData, $listadoProductos);

            DB::commit();

            return $solicitud;
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Error al insertar' => [$e->getMessage() . ' ' . $e->getLine()]]);
        }
    }

    public function actualizarSolicitudExamen(array $data, $id)
    {
        if (!$this->solicitudExamenRequest instanceof Request) {
            $validator = Validator::make($data, $this->solicitudExamenRequest->rules());

            if ($validator->fails()) throw new \Exception($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Log::channel('testing')->info('Log', ['data', $data]);
            $solicitud = SolicitudExamen::find($id);
            $solicitud->update($data);

            $existenCambiosFechaHora = 0;

            foreach ($data['examenes_solicitados'] as $examenSolicitado) {
                $examen['examen_id'] = $examenSolicitado['examen'];
                $examen['laboratorio_clinico_id'] = $examenSolicitado['laboratorio_clinico'];
                $examen['fecha_hora_asistencia'] = $examenSolicitado['fecha_hora_asistencia'];
                $examen['solicitud_examen_id'] = $solicitud->id;

                $modelo = EstadoSolicitudExamen::find($examenSolicitado['id']); // Examen solicitado
                $modelo->fill($examen);

                $existenCambiosFechaHora += $modelo->isDirty() ? 1 : 0;

                if ($modelo->isDirty()) $modelo->save();
            }

            if ($existenCambiosFechaHora) $this->notificarAlSolicitante($solicitud);

            // Log::channel('testing')->info('Log', ['cantidadCambiosFechaHora', $existenCambiosFechaHora]);

            DB::commit();

            return $solicitud;
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Error al insertar' => [$e->getMessage() . ' ' . $e->getLine()]]);
        }
    }

    private function notificarAlSolicitante(SolicitudExamen $solicitud_examen)
    {
        // NO BORRAR - HABILITAR ESTO EN PRODUCCION
        // Enviar email al solicitante
        Mail::to($solicitud_examen->solicitante->user->email)->send(new CambioFechaHoraSolicitudExamenMail($solicitud_examen));
        // Notificar sistema
        event(new CambioFechaHoraSolicitudExamenEvent($solicitud_examen, $solicitud_examen->autorizador_id, $solicitud_examen->solicitante_id));
        event(new ActualizarNotificacionesEvent());
    }

    public function obtenerSolicitudExamenPorRegistro(int $registro_empleado_examen_id)
    {
        return SolicitudExamen::where('registro_empleado_examen_id', $registro_empleado_examen_id)->get();
    }

    public function obtenerIdsExamenes(int $registro_empleado_examen_id): array
    {
        $solicitudes_examenes = $this->obtenerSolicitudExamenPorRegistro($registro_empleado_examen_id);
        $ids_examenes_solicitados = [];

        foreach ($solicitudes_examenes as $solicitud_examen) {
            $examenes = $solicitud_examen->examenesSolicitados->pluck('examen_id')->toArray();
            array_push($ids_examenes_solicitados, ...$examenes);
        }

        return $ids_examenes_solicitados;
    }

    public function obtenerIdsExamenesSolicitados(int $registro_empleado_examen_id): array
    {
        $solicitudes_examenes = $this->obtenerSolicitudExamenPorRegistro($registro_empleado_examen_id);
        $ids_examenes_solicitados = [];

        foreach ($solicitudes_examenes as $solicitud_examen) {
            $examenes = $solicitud_examen->examenesSolicitados->pluck('id')->toArray();
            array_push($ids_examenes_solicitados, ...$examenes);
        }

        return $ids_examenes_solicitados;
    }

    public function encontrarIdExamenSolicitadoBueno($solicitudes_examenes, int $examen_id)
    {
        foreach ($solicitudes_examenes as $solicitud_examen) {
            $examenSolicitado = $solicitud_examen->examenesSolicitados->first(function ($examen_solicitado) use ($examen_id, $solicitud_examen) {

                /* Log::channel('testing')->info('Log', ['solicitud_examen dentro', $solicitud_examen]);
                Log::channel('testing')->info('Log', ['examen_solicitado dentro', $examen_solicitado]);
                Log::channel('testing')->info('Log', ['examen_id dentro', $examen_id]); */
                return $examen_solicitado->examen_id === $examen_id;
            });
            return $examenSolicitado?->id;
        }
    }

    public function encontrarIdExamenSolicitado($solicitudes_examenes, int $examen_id)
    {
        foreach ($solicitudes_examenes as $solicitud_examen) {
            $examenSolicitado = $solicitud_examen->examenesSolicitados->first(function ($examen_solicitado) use ($examen_id, $solicitud_examen) {

                /* Log::channel('testing')->info('Log', ['solicitud_examen dentro', $solicitud_examen]);
                Log::channel('testing')->info('Log', ['solicitud_examen->examenesSolicitados dentro', $solicitud_examen->examenesSolicitados]);
                Log::channel('testing')->info('Log', ['solicitud_examen->examenesSolicitados contar dentro', $solicitud_examen->examenesSolicitados->count()]);
                Log::channel('testing')->info('Log', ['examen_solicitado dentro', $examen_solicitado]);
                Log::channel('testing')->info('Log', ['examen_id dentro', $examen_id]); */
                return $examen_solicitado->examen_id === $examen_id;
            });

        }
        return $examenSolicitado?->id;
    }
}
