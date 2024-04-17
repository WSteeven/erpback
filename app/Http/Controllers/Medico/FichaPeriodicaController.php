<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPeriodicaRequest;
use App\Http\Resources\Medico\FichaPeriodicaResource;
use App\Models\Medico\AccidenteEnfermedadLaboral;
use App\Models\Medico\FichaPeriodica;
use App\Models\Medico\RegistroEmpleadoExamen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaPeriodicaService;
use Src\Shared\Utils;

class FichaPeriodicaController extends Controller
{
    private $entidad = 'Ficha Periodica';

    public function __construct()
    {
        $this->middleware('can:puede.ver.fichas_periodicas_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_periodicas_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.fichas_periodicas_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.fichas_periodicas_preocupacionales')->only('destroy');
        // $this->middleware('can:puede.ver.fichas_periodicas')->only('index', 'show');
        // $this->middleware('can:puede.crear.fichas_periodicas')->only('store');
        // $this->middleware('can:puede.editar.fichas_periodicas')->only('update');
        // $this->middleware('can:puede.eliminar.fichas_periodicas')->only('destroy');
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
            $ficha_service->agregarHabitosToxicos($ficha, $request->habitos_toxicos);
            $ficha_service->agregarActividadesFisicas($request->actividades_fisicas);
            $ficha_service->agregarMedicaciones($request->medicaciones);
            $ficha_service->agregarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            $ficha_service->agregarAntecedentesFamiliares($request->atecedentes_personales);
            $ficha_service->agregarFrPuestosTrabajo($request->fr_puestos_trabajos_actuales);
            $ficha_service->agregarExamenes($request->examenes);
            $registro_empleado = RegistroEmpleadoExamen::find($datos['registro_empleado_examen_id']);
            $ficha_service->insertarAccidenteEnfermedadLaboral([
                'tipo' => AccidenteEnfermedadLaboral::ACCIDENTE_TRABAJO,
                'observacion' => $request->observacion,
                'calificado_iss' => $request->calificado_iss,
                'instituto_seguridad_social' => $request->instituto_seguridad_social,
                'descripcion' => $request->descripcion,
                'fecha' => $request->fecha,
                'tipo_descripcion_antecedente_trabajo' => $request->tipo_descripcion_antecedente_trabajo,
            ]);
            
            $ficha_service->insertarConstanteVital([
                'presion_arterial' => $request->presion_arterial,
                'temperatura' => $request->temperatura,
                'frecuencia_cardiaca' => $request->frecuencia_cardiaca,
                'saturacion_oxigeno' => $request->saturacion_oxigeno,
                'frecuencia_respiratoria' => $request->frecuencia_respiratoria,
                'peso' => $request->peso,
                'estatura' => $request->estatura,
                'talla' => $request->talla,
                'indice_masa_corporal' => $request->indice_masa_corporal,
                'perimetro_abdominal' => $request->perimetro_abdominal,
            ]);
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
    public function show($id)
    {
        //
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
}
