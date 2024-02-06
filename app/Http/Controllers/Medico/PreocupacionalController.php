<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\PreocupacionalRequest;
use App\Http\Resources\Medico\PreocupacionalResource;
use App\Models\Empleado;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\Preocupacional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\PreocupacionalService;
use Src\Shared\Utils;

class PreocupacionalController extends Controller
{
    private $entidad = 'Preocupacional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.preocupacionales')->only('store');
        $this->middleware('can:puede.editar.preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Preocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(PreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $preocupacional = Preocupacional::create($datos);
            $preocupacional_service = new PreocupacionalService($preocupacional->id);
            $preocupacional_service->agregarHabitosToxicos($request->habitos_toxicos);
            $preocupacional_service->agregarEstiloVida($request->estilos_vida);
            $preocupacional_service->agregarMedicacion($request->medicaciones);
            $preocupacional_service->agregarActividadPuestoTrabajo($request->actividades_puestos_trabajos);
            $preocupacional_service->agregarExamenesExpecificos($request->examenes_especificos);
            $preocupacional_service->agregarDiagnosticos($request->diagnosticos);
            $preocupacional_service->agregarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            $preocupacional_service->insertarAntecedentesPersonales(new AntecedentePersonal([
                'antecedentes_quirorgicos' => $request->antecedentes_quirorgicos,
                'vida_sexual_activa' => $request->vida_sexual_activa,
                'tiene_metodo_planificacion_familiar' => $request->tiene_metodo_planificacion_familiar,
                'tipo_metodo_planificacion_familiar' => $request->tipo_metodo_planificacion_familiar,
            ]));
            $preocupacional_service->agregarExamenes($request->examenes);
            $empleado = Empleado::find($preocupacional->empleado_id);
            $genero = $empleado?->genero;
            if ($genero === 'F') {
                $preocupacional_service->insertarAntecedentesGinecoObstetricos(new AntecedenteGinecoObstetrico([
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
            $preocupacional_service->insertarDescripcionAntecedenteTrabajo(
                new DescripcionAntecedenteTrabajo([
                    'calificado_iess' => $request->calificado_iess,
                    'descripcion' => $request->descripcion,
                    'fecha' => $request->fecha,
                    'observacion' => $request->observacion,
                    'tipo_descripcion_antecedente_trabajo' => $request->tipo_descripcion_antecedente_trabajo,
                ])
            );
            $preocupacional_service->insertarConstanteVital(new ConstanteVital([
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
            $modelo = new PreocupacionalResource($preocupacional);
            $this->tabla_roles($preocupacional);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
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

    public function show(PreocupacionalRequest $request, Preocupacional $preocupacional)
    {
        $modelo = new PreocupacionalResource($preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(PreocupacionalRequest $request, Preocupacional $preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $preocupacional->update($datos);
            $modelo = new PreocupacionalResource($preocupacional->refresh());
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

    public function destroy(PreocupacionalRequest $request, Preocupacional $preocupacional)
    {
        try {
            DB::beginTransaction();
            $preocupacional->delete();
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
