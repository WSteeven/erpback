<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaRetiroRequest;
use App\Http\Resources\Medico\FichaRetiroResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Empleado;
use App\Models\Medico\CategoriaExamenFisico;
use App\Models\Medico\ConsultaMedica;
use App\Models\Medico\FichaRetiro;
use App\Models\Medico\ProfesionalSalud;
use App\Models\Medico\RegistroEmpleadoExamen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\fichaRetiroService;
use Src\Shared\Utils;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Src\App\Medico\FichasMedicasService;

class FichaRetiroController extends Controller
{
    private $entidad = 'Ficha de Retiro';
    private FichasMedicasService $fichas_medicas_service;

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_retiros')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_retiros')->only('store');
        $this->middleware('can:puede.editar.fichas_retiros')->only('update');
        $this->middleware('can:puede.eliminar.fichas_retiros')->only('destroy');

        $this->fichas_medicas_service = new FichasMedicasService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = FichaRetiro::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FichaRetiroRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $ficha = FichaRetiro::create($datos);
            $ficha_service = new FichaRetiroService($ficha);
            $ficha_service->guardarDatosFichaRetiro($request);

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaRetiroResource($ficha);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Exception $e) {
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
    public function show(FichaRetiro $ficha_retiro)
    {
        $modelo = new FichaRetiroResource($ficha_retiro);
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
        $fecha_inicio_labores = $empleado->fecha_ingreso ? Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : null;
        $fecha_salida = $empleado->fecha_salida ? Carbon::parse($empleado->fecha_salida)->format('Y-m-d') : null;

        $modelo = [
            'motivo_consulta' => 'Ficha reintegro',
            'cargo' => $cargo_id,
            'fecha_inicio_labores' => $fecha_inicio_labores,
            'fecha_salida' => $fecha_salida,
        ];

        return response()->json(compact('modelo'));
    }

    public function imprimirPDF(FichaRetiro $ficha_retiro)
    {
        /* request()->validate([
            'registro_empleado_examen_id' => 'required|numeric|integer|exists:med_registros_empleados_examenes,id'
        ]); */

        // $registro_empleado_examen_id = request('registro_empleado_examen_id');
        // $registro_empleado_examen = RegistroEmpleadoExamen::find($registro_empleado_examen_id);
        $registro_empleado_examen_id = $ficha_retiro->registro_empleado_examen_id;

        $configuracion = ConfiguracionGeneral::first();

        $ficha_retiro_service = new fichaRetiroService();
        // $ficha['resultados_examenes'] = $this->fichas_medicas_service->consultarResultadosExamenes($registro_empleado_examen_id);
        // $resultados_examenes = $ficha_retiro_service->consultarResultadosExamenes($registro_empleado_examen);
        // Log::channel('testing')->info($resultados_examenes);

        // $resource = new FichaAptitudResource($ficha_aptitud);
        // $empleado = Empleado::find($registro_empleado_examen->empleado_id); // $ficha_aptitud->registroEmpleadoExamen->empleado_id);
        $empleado = Empleado::find($ficha_retiro->registroEmpleadoExamen->empleado_id);
        $profesionalSalud = ProfesionalSalud::find(116); //$ficha_aptitud->profesional_salud_id);
        // $idEmpleado = $registro_empleado_examen->empleado_id;
        $idEmpleado = $empleado->id;

        /* $consultasMedicas = ConsultaMedica::whereHas('registroEmpleadoExamen', function ($query) use ($idEmpleado) {
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
        }); */

        $actividadesFactorRiesgo = [
            [
                'actividad' => '',
                'factor_riesgo' => '',
            ],
            [
                'actividad' => '',
                'factor_riesgo' => '',
            ],
            [
                'actividad' => '',
                'factor_riesgo' => '',
            ],
        ];

        /* $fichaRetiro = [
            'consultasMedicas' => $consultasMedicas,
            'resultados_examenes' => $this->fichas_medicas_service->consultarResultadosExamenes($registro_empleado_examen_id),
            'antecedentes_clinicos_quirurjicos' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'accidentes_trabajo' => [
                'especificar' => 'IESS',
                'calificado_iess' => true,
                'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'fecha' => Carbon::parse('2024-04-20'),
            ],
            'enfermedades_profesionales' => [
                'especificar' => 'IESS',
                'calificado_iess' => false,
                'observaciones' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
                'fecha' => Carbon::parse('2024-03-24'),
            ],
            'observaciones_examen_fisico_regional' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
            'examenes_fisicos_regionales' => [1, 5, 8, 35, 32, 30],
            'observaciones_resultados_examenes' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry.',
            'resultados_examenes' => [
                [
                    'examen' => 'TGO',
                    'fecha' => '1996-04-20',
                    'resultado' => 'TODO BIEN',
                ],
                [
                    'examen' => 'TGP',
                    'fecha' => '1996-04-21',
                    'resultado' => 'TODO BIEN OTRA VEZ',
                ],
            ],
            'diagnosticos' => [
                [
                    'cie' => 'TGO',
                    'descripcion' => 'TODO BIEN',
                    'pre' => 'bien',
                    'def' => 'tambien',
                ],
                [
                    'cie' => 'TGP',
                    'descripcion' => 'TODO BIEN OTRA VEZ',
                    'pre' => 'bien',
                    'def' => 'tambien',
                ],
            ],
            'se_hizo_evaluacion_retiro' => true,
            'observaciones_evaluacion_retiro' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.',
            'recomendaciones_tratamientos' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has.',
            'fecha_creacion' => Carbon::parse('2024-04-20')->format('Y-m-d'),
            'hora_creacion' => Carbon::parse('2024-04-20 10:54:14')->format('H:i:s'),
        ]; */


        $regiones = CategoriaExamenFisico::with('region')->select('id', 'nombre', 'region_cuerpo_id')->get()->groupBy(function ($item) {
            return $item->region?->nombre;
        })->map(function ($items, $region) {
            return [
                'region' => $region,
                'categorias' => $items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nombre' => $item->nombre,
                    ];
                })->toArray(),
            ];
        })->values();

        // Log::channel('testing')->info($regiones);
        /*[
            [
                'region' => 'Piel',
                'tipos' => [
                    'a. Cicatrices'
                ],
            ],
        ];*/
        $resource = new FichaRetiroResource($ficha_retiro);
        $ficha = $resource->resolve();

        $ficha['observaciones_examen_fisico_regional'] = $this->fichas_medicas_service->mapearObservacionesExamenFisicoRegional($ficha['examenes_fisicos_regionales']); // primero va esto
        $ficha['examenes_fisicos_regionales'] = $ficha['examenes_fisicos_regionales']->pluck('categoria_examen_fisico_id')->toArray(); // Luego esto
        // Log::channel('testing')->info('Examenes', ['', $ficha['examenes_fisicos_regionales']]);
        $ficha['resultados_examenes'] = $this->fichas_medicas_service->consultarResultadosExamenes($registro_empleado_examen_id);

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

        $ficha['consultas_medicas'] = $consultasMedicasMapeado;
        $ficha['recomendaciones_tratamiento'] = count($consultasMedicas) ? $consultasMedicas[0]?->receta->rp . ' / ' . $consultasMedicas[0]?->receta->prescripcion : '';

        $datos = [
            'configuracion' => $configuracion,
            'empleado' => $empleado,
            'profesionalSalud' => $profesionalSalud,
            // 'firmaProfesionalMedico' => 'data:image/png;base64,' . base64_encode(file_get_contents(substr($profesionalSalud->empleado->firma_url, 1))),
            'actividadesFactorRiesgo' => $actividadesFactorRiesgo,
            'ficha_retiro' => $ficha,
            'regiones' => $regiones,
            'fecha_creacion' => Carbon::parse($ficha_retiro->created_at)->format('Y-m-d'),
            'hora_creacion' => Carbon::parse($ficha_retiro->created_at)->format('H:i:s'),
        ];

        try {
            $pdf = Pdf::loadView('medico.pdf.ficha_retiro', $datos);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption(['isRemoteEnabled' => true]);
            $pdf->render();

            $file = $pdf->output();
            return $file;
            // return view('medico.pdf.ficha_retiro', $datos);
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
            $mensaje = $ex->getMessage() . '. ' . $ex->getLine();
            return response()->json(compact('mensaje'));
        }
    }
}
