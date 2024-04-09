<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\FichaPreocupacionalRequest;
use App\Http\Resources\Medico\FichaPreocupacionalResource;
use App\Models\Empleado;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use App\Models\Medico\AntecedentePersonal;
use App\Models\Medico\ConstanteVital;
use App\Models\Medico\DescripcionAntecedenteTrabajo;
use App\Models\Medico\FichaPreocupacional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\FichaPreocupacionalService;
use Src\Shared\Utils;

class FichaPreocupacionalController extends Controller
{
    private $entidad = 'FichaPreocupacional';

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
            $ficha_preocupacional_service->agregarHabitosToxicos($request->habitos_toxicos);
            $ficha_preocupacional_service->agregarEstiloVida($request->estilos_vida);
            $ficha_preocupacional_service->agregarMedicacion($request->medicaciones);
            $ficha_preocupacional_service->agregarActividadPuestoTrabajo($request->actividades_puestos_trabajos);
            $ficha_preocupacional_service->agregarAntecedentesEmpleosAnteriores($request->antecedentes_empleos_anteriores);
            $ficha_preocupacional_service->insertarAntecedentesPersonales(new AntecedentePersonal([
                'antecedentes_quirorgicos' => $request->antecedentes_quirorgicos,
                'vida_sexual_activa' => $request->vida_sexual_activa,
                'tiene_metodo_planificacion_familiar' => $request->tiene_metodo_planificacion_familiar,
                'tipo_metodo_planificacion_familiar' => $request->tipo_metodo_planificacion_familiar,
            ]));
            $ficha_preocupacional_service->agregarExamenes($request->examenes);
            $empleado = Empleado::find($ficha_preocupacional->empleado_id);
            $genero = $empleado?->genero;
            if ($genero === 'F') {
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
            $modelo = new FichaPreocupacionalResource($ficha_preocupacional);
            $this->tabla_roles($ficha_preocupacional);
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
