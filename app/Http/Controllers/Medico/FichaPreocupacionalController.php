<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Http\Resources\Medico\FichaPreocupacionalResource;
use App\Http\Resources\Medico\RegistroEmpleadoExamenResource;
use App\Models\Empleado;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\FichaPreocupacional;
use App\Models\Medico\FrPuestoTrabajoActual;
use App\Models\Medico\RegistroEmpleadoExamen;
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
        $this->middleware('can:puede.ver.fichas_periodicas_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.fichas_periodicas_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.fichas_periodicas_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.fichas_periodicas_preocupacionales')->only('destroy');
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
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional->id);
            $ficha_preocupacional_service->insertarAntecedentePersonal(new AntecedentePersonal([
                'antecedentes_quirurgicos' => $request->antecedentes_quirurgicos,
                'vida_sexual_activa' => $request->vida_sexual_activa,
                'tiene_metodo_planificacion_familiar' => $request->tiene_metodo_planificacion_familiar,
                'tipo_metodo_planificacion_familiar' => $request->tipo_metodo_planificacion_familiar,
            ]));
            $ficha_preocupacional_service->agregarHabitosToxicos($request->habitos_toxicos);
            $ficha_preocupacional_service->agregarActividadesFisicas($request->actividades_fisicas);
            $ficha_preocupacional_service->agregarMedicaciones($request->medicaciones);
            $ficha_preocupacional_service->agregarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            if (!is_null($request->atecedentes_personales)) $ficha_preocupacional_service->agregarAntecedentesFamiliares($request->atecedentes_personales);
            $ficha_preocupacional_service->agregarFrPuestosTrabajo($request->fr_puestos_trabajos_actuales);
            $ficha_preocupacional_service->agregarExamenes($request->examenes);
            $registro_empleado = RegistroEmpleadoExamen::find($datos['registro_empleado_examen_id']);
            $genero = $registro_empleado->empleado?->genero;
            if ($genero === Empleado::FEMENINO) {
                $ficha_preocupacional_service->insertarAntecedentesGinecoObstetricos(new AntecedenteGinecoObstetrico([
                    'menarquia' => $request->menarquia,
                    'ciclos' => $request->ciclos,
                    'fecha_ultima_menstruacion' => $request->fecha_ultima_menstruacion,
                    'gestas' => $request->gestas,
                    'partos' => $request->partos,
                    'cesareas' => $request->cesareas,
                    'abortos' => $request->abortos,
                    'hijos_vivos' => $request->hijos_vivos,
                    'hijos_muertos' => $request->hijos_muertos,
                ]));
            }
            $ficha_preocupacional_service->insertarDescripcionAntecedenteTrabajo(
                new DescripcionAntecedenteTrabajo([
                    'calificado_iess' => $request->calificado_iess,
                    'descripcion' => $request->descripcion,
                    'fecha' => $request->fecha,
                    'observacion' => $request->observacion,
                    'tipo_descripcion_antecedente_trabajo' => $request->tipo_descripcion_antecedente_trabajo,
                ])
            );
            $ficha_preocupacional_service->insertarConstanteVital(new ConstanteVital([
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
            ]));
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getLine(), $e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de preocupacional' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(FichaPreocupacionalRequest $request, FichaPreocupacional $ficha_preocupacional)
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
            $ficha_preocupacional_service = new FichaPreocupacionalService($ficha_preocupacional->id);
            $ficha_preocupacional_service->actualizarHabitosToxicos($request->habitos_toxicos);
            $ficha_preocupacional_service->actualizarActividadesFisicas($request->actividades_fisicas);
            $ficha_preocupacional_service->actualizarMedicaciones($request->medicaciones);
            $ficha_preocupacional_service->actualizarActividadesPuestoTrabajo($request->actividades_puestos_trabajos);
            $ficha_preocupacional_service->actualizarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            $ficha_preocupacional_service->insertarAntecedentePersonal($request->antecedente_personal);
            $ficha_preocupacional_service->actualizarExamenes($request->examenes);
            $ficha_preocupacional_service->actualizarFrPuestosTrabajo($request->fr_puestos_trabajos_actuales);
            $ficha_preocupacional_service->actualizarAntecedentesFamiliares($request->atecedentes_familiares);
            $registro_empleado = RegistroEmpleadoExamen::find($datos['registro_empleado_examen_id']);
            $genero = $registro_empleado->empleado?->genero;
            if ($genero === Empleado::FEMENINO) {
                $ficha_preocupacional_service->insertarAntecedentesGinecoObstetricos($request->antecedente_ginecoobstetrico);
            }
            $ficha_preocupacional_service->insertarDescripcionAntecedenteTrabajo($request->descripcion_antecedente_trabajo);
            $ficha_preocupacional_service->insertarConstanteVital($request->contante_vital);
            $ficha_preocupacional_service->insertarFrPuestoTrabajo($request->fr_puesto_trabajo);
            $ficha_preocupacional_service->actualizarDetalleCategFactorRiesgoFrPuestoTrabajoAct($request->detalles_categorias_factores_riesgos_fr_puesto_trabajo_actual);
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de preocupacional' . $e->getMessage() . ' ' . $e->getLine()], 422);
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
}
