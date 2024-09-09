<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DiagnosticoRecetaRequest;
use App\Http\Resources\Medico\CitaMedicaResource;
use App\Http\Resources\Medico\DiagnosticoRecetaResource;
use App\Http\Resources\Medico\RecetaResource;
use App\Models\Medico\CitaMedica;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\DiagnosticoCitaMedica;
use App\Models\Medico\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

// ELIMINAR
class DiagnosticoRecetaController extends Controller
{
    private $entidad = 'Diagnóstico receta';

    public function store(DiagnosticoRecetaRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $datos = $request->validated();
            // Log::channel('testing')->info('Log', ['diagn', 'diagnosticos gerger']);

            // Log::channel('testing')->info('Log', ['diagn', 'diagnosticos']);
            // Log::channel('testing')->info('Log', ['diagn', $datos['diagnosticos']]);
            $receta = Receta::create([
                'rp' => $datos['rp'],
                'prescripcion' => $datos['prescripcion'],
                'cita_medica_id' => $datos['cita_medica'],
                'registro_empleado_examen_id' => $datos['registro_empleado_examen'],
            ]);


            foreach ($datos['diagnosticos'] as $diagnostico) {
                // Log::channel('testing')->info('Log', ['cie 1', $diagnostico['cie']]);
                DiagnosticoCitaMedica::create([
                    'recomendacion' => $diagnostico['recomendacion'],
                    'cie_id' => $diagnostico['cie'],
                    'cita_medica_id' => $datos['cita_medica'],
                    'registro_empleado_examen_id' => $datos['registro_empleado_examen'],
                ]);
            }

            // cita atendida
            $citaMedica = ConsultaMedica::find($datos['cita_medica']);
            $citaMedica->estado_cita_medica = CitaMedica::ATENDIDO;
            $citaMedica->save();

            $modelo = new CitaMedicaResource($citaMedica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
