<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPeriodicaRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\Medico\FichaPeriodicaResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\FichaPeriodica;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\RegistroEmpleadoExamen;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaPeriodicaService;
use Src\App\Medico\FichasMedicasService;
use Src\Shared\Utils;

class FichaPeriodicaController extends Controller
{
    private string $entidad = 'Ficha periodica';
    private FichasMedicasService $fichasMedicasService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_periodicas')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_periodicas')->only('store');
        $this->middleware('can:puede.editar.fichas_periodicas')->only('update');
        $this->middleware('can:puede.eliminar.fichas_periodicas')->only('destroy');

        $this->fichasMedicasService = new FichasMedicasService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = FichaPeriodica::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FichaPeriodicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha = FichaPeriodica::create($datos);
            $ficha_service = new FichaPeriodicaService($ficha);
            $ficha_service->guardarDatosFichaPeriodica($request);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaPeriodicaResource($ficha);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getLine(), $e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(FichaPeriodica $ficha_periodica)
    {
        $modelo = new FichaPeriodicaResource($ficha_periodica);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function consultarInformacionDefectoFicha()
    {
        request()->validate([
            'registro_empleado_examen_id' => 'required|numeric|integer|exists:med_registros_empleados_examenes,id'
        ]);

        $consulta_medica = ConsultaMedica::where('registro_empleado_examen_id', request('registro_empleado_examen_id'))->first();
        $cargo_id = RegistroEmpleadoExamen::find(request('registro_empleado_examen_id'))->empleado->cargo_id;
        // $consulta_medica = new ConsultaMedicaResource($consulta_medica);

        $modelo = [
            'motivo_consulta' => 'Ficha periódica',
            'cargo' => $cargo_id,
            // 'recomendaciones_tratamiento' => $consulta_medica?->receta->rp . '/' . $consulta_medica?->receta->prescripcion,
            // 'enfermedad_actual' => $consulta_medica?->diagnosticosCitaMedica->map(fn ($diagnostico) => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad)->implode(',', ' '),
        ];

        return response()->json(compact('modelo'));
    }

    public function imprimirPDF(FichaPeriodica $ficha_periodica)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaPeriodicaResource($ficha_periodica);
        $empleado = Empleado::find($ficha_periodica->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_periodica->profesional_salud_id);
        $idEmpleado = $empleado->id;
        $registro_empleado_examen_id = $ficha_periodica->registro_empleado_examen_id;

        $respuestasTiposEvaluacionesMedicasRetiros = [
            ['SI', 'NO'],
            ['PRESUNTIVA', 'DEFINITIVA', 'NO APLICA'],
            ['SI', 'NO', 'NO APLICA'],
        ];

        // Solo de la ficha actual
        $consultasMedicas = ConsultaMedica::where('registro_empleado_examen_id', $registro_empleado_examen_id)->latest()->get();

        Log::channel('testing')->info('Log', ['empleado', $empleado]);

        $consultasMedicasMapeado = $consultasMedicas->map(function ($consulta) {
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

        $ficha = $resource->resolve();

        $tipos_aptitudes_medicas_laborales = TipoAptitudMedicaLaboral::all()->map(function ($tipo) use ($ficha) {
            if ($tipo->id === $ficha['aptitud_medica']['tipo_aptitud_id']) $tipo->seleccionado = true;
            else $tipo->seleccionado = false;
            return $tipo;
        });

        $ficha['consultas_medicas'] = $consultasMedicasMapeado;
        $ficha['recomendaciones_tratamiento'] = count($consultasMedicas) ? $consultasMedicas[0]?->receta->rp . ' / ' . $consultasMedicas[0]?->receta->prescripcion : '';
        $ficha['resultados_examenes'] = $this->fichasMedicasService->consultarResultadosExamenes($registro_empleado_examen_id);
        $ficha['observaciones_aptitud_medica'] = 'observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica observaciones_aptitud_medica';

       /*  $ficha['accidentes_trabajo'] = [
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
        ]; */

        $ficha['observaciones_examen_fisico_regional'] = $this->fichasMedicasService->mapearObservacionesExamenFisicoRegional($ficha['examenes_fisicos_regionales']); //'observaciones_examen_fisico_regional observaciones_examen_fisico_regional observaciones_examen_fisico_regional';
        $ficha['examenes_fisicos_regionales'] = $ficha['examenes_fisicos_regionales']->pluck('categoria_examen_fisico_id')->toArray();

        $datos = [
            'ficha_preocupacional' => $ficha,
            'configuracion' => $configuracion,
            'empleado' => new EmpleadoResource($empleado),
            'profesionalSalud' => $profesionalSalud,
            'tipo_proceso_examen' => $ficha_periodica->registroEmpleadoExamen->tipo_proceso_examen,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
            'fecha_creacion' => Carbon::parse($ficha_periodica->created_at)->format('Y-m-d'),
            'hora_creacion' => Carbon::parse($ficha_periodica->created_at)->format('H:i:s'),
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_periodica', $datos);
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
