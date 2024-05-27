<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaReintegroRequest;
use App\Http\Resources\EmpleadoResource;
use App\Http\Resources\Medico\FichaReintegroResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\FichaReintegro;
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
use Src\App\Medico\FichaReintegroService;
use Src\App\Medico\FichasMedicasService;
use Src\Shared\Utils;

class FichaReintegroController extends Controller
{
    private $entidad = 'Ficha reintegro';
    private FichasMedicasService $fichas_medicas_service;

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_reintegro')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_reintegro')->only('store');
        $this->middleware('can:puede.editar.fichas_reintegro')->only('update');
        $this->middleware('can:puede.eliminar.fichas_reintegro')->only('destroy');

        $this->fichas_medicas_service = new FichasMedicasService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = FichaReintegro::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FichaReintegroRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha = FichaReintegro::create($datos);
            $ficha_service = new FichaReintegroService($ficha);
            $ficha_service->guardarDatosFichaReintegro($request);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaReintegroResource($ficha);
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
    public function show(FichaReintegro $ficha_reintegro)
    {
        $modelo = new FichaReintegroResource($ficha_reintegro);
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
        $empleado = RegistroEmpleadoExamen::find(request('registro_empleado_examen_id'))->empleado;
        $cargo_id = $empleado->cargo_id;
        $fecha_reingreso = $empleado->fecha_ingreso ? Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : null;
        $fecha_ultimo_dia_laboral = $empleado->fecha_salida ? Carbon::parse($empleado->fecha_salida)->format('Y-m-d') : null;
        // $consulta_medica = new ConsultaMedicaResource($consulta_medica);

        $modelo = [
            'motivo_consulta' => 'Ficha reintegro',
            'cargo' => $cargo_id,
            'fecha_reingreso' => $fecha_reingreso,
            'fecha_ultimo_dia_laboral' => $fecha_ultimo_dia_laboral,
        ];

        return response()->json(compact('modelo'));
    }

    public function imprimirPDF(FichaReintegro $ficha_reintegro)
    {
        $configuracion = ConfiguracionGeneral::first();
        $resource = new FichaReintegroResource($ficha_reintegro);
        $empleado = Empleado::find($ficha_reintegro->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find($ficha_reintegro->profesional_salud_id);
        $idEmpleado = $empleado->id;
        $registro_empleado_examen_id = $ficha_reintegro->registro_empleado_examen_id;

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
        $ficha['total_dias'] = Carbon::parse($ficha_reintegro->fecha_reingreso)->diffInDays(Carbon::parse($ficha_reintegro->fecha_ultimo_dia_laboral));
        $ficha['recomendaciones_tratamiento'] = count($consultasMedicas) ? $consultasMedicas[0]?->receta->rp . ' / ' . $consultasMedicas[0]?->receta->prescripcion : '';
        $ficha['enfermedad_actual'] = count($consultasMedicas) ? $consultasMedicas[0]?->diagnosticosCitaMedica->map(fn ($diagnostico) => $diagnostico->cie->codigo . '-' . $diagnostico->cie->nombre_enfermedad)->implode(',', ' ') : '';
        $ficha['resultados_examenes'] = $this->fichas_medicas_service->consultarResultadosExamenes($registro_empleado_examen_id);
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

        $ficha['observaciones_examen_fisico_regional'] = $this->fichas_medicas_service->mapearObservacionesExamenFisicoRegional($ficha['examenes_fisicos_regionales']); //'observaciones_examen_fisico_regional observaciones_examen_fisico_regional observaciones_examen_fisico_regional';
        $ficha['examenes_fisicos_regionales'] = $ficha['examenes_fisicos_regionales']->pluck('categoria_examen_fisico_id')->toArray();

        $datos = [
            'ficha_reintegro' => $ficha,
            'configuracion' => $configuracion,
            'empleado' => new EmpleadoResource($empleado),
            'profesionalSalud' => $profesionalSalud,
            'tipo_proceso_examen' => $ficha_reintegro->registroEmpleadoExamen->tipo_proceso_examen,
            'tipos_aptitudes_medicas_laborales' => $tipos_aptitudes_medicas_laborales,
            'fecha_creacion' => Carbon::parse($ficha_reintegro->created_at)->format('Y-m-d'),
            'hora_creacion' => Carbon::parse($ficha_reintegro->created_at)->format('H:i:s'),
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_reintegro', $datos);
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
