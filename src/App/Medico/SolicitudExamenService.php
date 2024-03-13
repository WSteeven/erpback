<?php

namespace Src\App\Medico;

use App\Http\Requests\Medico\SolicitudExamenRequest;
use App\Models\Medico\EstadoSolicitudExamen;
use App\Models\Medico\SolicitudExamen;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SolicitudExamenService
{
    protected $solicitudExamenRequest;

    public function __construct() //SolicitudExamenRequest $solicitudExamenRequest)
    {
        $this->solicitudExamenRequest = new SolicitudExamenRequest(); //$solicitudExamenRequest;
    }

    public function crearSolicitudExamen(array $data)
    {
        // Si se llama al método desde el controlador, no validar dentro del servicio
        if (!$this->solicitudExamenRequest instanceof Request) { //->isCalledFromController()) {
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

            if ($existenCambiosFechaHora) $this->notificarAlSolicitante();

            Log::channel('testing')->info('Log', ['cantidadCambiosFechaHora', $existenCambiosFechaHora]);

            DB::commit();

            return $solicitud;
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['Error al insertar' => [$e->getMessage() . ' ' . $e->getLine()]]);
        }
    }

    private function notificarAlSolicitante()
    {
        // enviar email

        // notificar sistema

    }
}
