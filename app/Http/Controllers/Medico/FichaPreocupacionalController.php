<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Http\Resources\Medico\ConsultaMedicaResource;
use App\Http\Resources\Medico\FichaPreocupacionalResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\RegistroEmpleadoExamen;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use App\Models\Medico\TipoEvaluacionMedicaRetiro;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaPreocupacionalService;
use Src\Shared\Utils;

class FichaPreocupacionalController extends Controller
{
    private $entidad = 'FichaPreocupacional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.fichas_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.fichas_preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = FichaPreocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(FichaPreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha_preocupacional = FichaPreocupacional::create($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->guardarDatosFichaPreocupacional($request);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getLine(), $e->getMessage()],
            ]);
        }
    }

    public function show(FichaPreocupacional $ficha_preocupacional)
    {
        $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $ficha_preocupacional->update($datos);
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional);
            $ficha_preocupacional_service->actualizarDatosFichaPreocupacional($request);

            $modelo = new FichaPreocupacionalResource($ficha_preocupacional->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al actualizar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function destroy(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
    {
        try {
            DB::beginTransaction();
            $ficha_preocupacional->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de preocupacional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function consultarInformacionDefectoFicha()
    {
        request()->validate([
            'registro_empleado_examen_id' => 'required|numeric|integer|exists:med_registros_empleados_examenes,id'
        ]);

        $consulta_medica = ConsultaMedica::where('registro_empleado_examen_id', request('registro_empleado_examen_id'))->first();
        // $consulta_medica = new ConsultaMedicaResource($consulta_medica);

        $modelo = [
            'motivo_consulta' => 'Ficha preocupacional',
            'recomendaciones_tratamiento' => $consulta_medica->receta->rp . '/' . $consulta_medica->receta->prescripcion,
            'enfermedad_actual' => $consulta_medica->diagnosticosCitaMedica->map(fn ($diagnostico) => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad)->implode(',', ' '),
        ];

        return response()->json(compact('modelo'));
    }

    public function imprimirPDF(FichaPreocupacional $ficha_preocupacional)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaPreocupacionalResource($ficha_preocupacional);
        $empleado = Empleado::find($ficha_preocupacional->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_preocupacional->profesional_salud_id);
        $idEmpleado = $empleado->id;

        $respuestasTiposEvaluacionesMedicasRetiros = [
            ['SI', 'NO'],
            ['PRESUNTIVA', 'DEFINITIVA', 'NO APLICA'],
            ['SI', 'NO', 'NO APLICA'],
        ];

        $consultasMedicas = ConsultaMedica::whereHas('registroEmpleadoExamen', function ($query) use ($idEmpleado) {
            $query->where('empleado_id', $idEmpleado);
        })->orWhereHas('citaMedica', function ($query) use ($idEmpleado) {
            $query->where('paciente_id', $idEmpleado);
        })->latest()->get();

        $consultasMedicas = $consultasMedicas->map(function ($consulta) {
            return [
                'observacion' => $consulta->observacion,
                'diagnosticos' => $consulta->diagnosticosCitaMedica->map(function ($diagnostico) {
                    return [
                        'recomendacion' => $diagnostico->recomendacion,
                        'cie' => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad,
                        'pre' => null,
                        'def' => null,
                    ];
                }),
            ];
        });



        /* $opcionesRespuestasTipoEvaluacionMedicaRetiro = TipoEvaluacionMedicaRetiro::all()->map(function ($tipo, $index) use ($respuestasTiposEvaluacionesMedicasRetiros, $ficha_aptitud) {
            return [
                'id' => $tipo->id,
                'nombre' => $tipo->nombre,
                'posibles_respuestas' => $respuestasTiposEvaluacionesMedicasRetiros[$index],
                'respuesta' => $ficha_preocupacional->opcionesRespuestasTipoEvaluacionMedicaRetiro->first(fn ($opcion) => $opcion->tipo_evaluacion_medica_retiro_id === $tipo->id)->respuesta,
            ];
        }); */

        /* $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha_aptitud) {
            if ($tipo->id === $ficha_aptitud->tipo_aptitud_medica_laboral_id) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        }); */

        $ficha = $resource->resolve();

        $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha) {
            if ($tipo->id === $ficha['aptitud_medica']['tipo_aptitud_id']) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        });

        // $ficha['tipos_aptitudes_medicas_laborales'] = $tipos_aptitudes_medicas_laborales;

        $ficha['consultas_medicas'] = $consultasMedicas;
        $ficha['observaciones_aptitud_medica'] = 'observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica';

        $ficha['accidentes_trabajo'] = [
            'especificar' => 'IESS',
            'calificado_iess' => true,
            'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'fecha' => Carbon::parse('2024-04-20'),
        ];

        $ficha['enfermedades_profesionales'] = [
            'especificar' => 'IESS',
            'calificado_iess' => false,
            'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'fecha' => Carbon::parse('2024-03-24'),
        ];

        $ficha['examenes_fisicos_regionales'] = $ficha['examenes_fisicos_regionales']->pluck('categoria_examen_fisico_id')->toArray();
        $ficha['observaciones_examen_fisico_regional'] = 'observaciones_examen_fisico_regional observaciones_examen_fisico_regional observaciones_examen_fisico_regional';

        $datos = [
            'ficha_preocupacional' => $ficha,
            'configuracion' => $configuracion,
            'empleado' => $empleado,
            'profesionalSalud' => $profesionalSalud,
            'tipo_proceso_examen' => $ficha_preocupacional->registroEmpleadoExamen->tipo_proceso_examen,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_preocupacional', $datos);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();

            $file = $pdf->output();
            return $file;
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'));
        }
    }
}
