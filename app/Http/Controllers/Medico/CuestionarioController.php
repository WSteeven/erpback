<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CuestionarioRequest;
use App\Http\Resources\Medico\CuestionarioEmpleadoResource;
use App\Http\Resources\Medico\CuestionarioResource;
use App\Models\Empleado;
use App\Models\Medico\Cuestionario;
use App\Models\Medico\Pregunta;
use App\Models\Medico\RespuestaCuestionarioEmpleado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\CuestionarioPisicosocialService;
use Src\Shared\Utils;

class CuestionarioController extends Controller
{
    private $entidad = 'Cuestionario';

    public function __construct()
    {
        $this->middleware('can:puede.ver.cuestionarios')->only('index', 'show');
        $this->middleware('can:puede.crear.cuestionarios')->only('store');
        $this->middleware('can:puede.editar.cuestionarios')->only('update');
        $this->middleware('can:puede.eliminar.cuestionarios')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Cuestionario::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CuestionarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CuestionarioRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $cuestionario = Cuestionario::create($datos);
            $modelo = new CuestionarioResource($cuestionario);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error en el servidor' => ['Mensaje: ' . $e->getMessage() . ' - Linea: ' . $e->getLine()],
            ]);
            // return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de Cuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function reportesCuestionarios(Request $request)
    {
        try {
            $results = [];
            $empleados = Empleado::habilitado()
                ->where('salario', '!=', 0)
                ->orderBy('apellidos', 'asc')
                ->with('canton', 'area')
                ->get();

            $results = CuestionarioEmpleadoResource::collection($empleados);

            if ($request->imprimir) {
                $preguntas = Pregunta::all(['id', 'pregunta', 'codigo']);
                $reportes_empaquetado = RespuestaCuestionarioEmpleado::empaquetar($empleados);
                $reporte = compact('preguntas', 'reportes_empaquetado');
                return CuestionarioPisicosocialService::imprimir_reporte($reporte);
            }

            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'Error en el servidor' => ['Mensaje: ' . $e->getMessage() . ' - Linea: ' . $e->getLine()],
            ]);
            // return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de Cuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Cuestionario32  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function show(Cuestionario $cuestionario)
    {
        $modelo = new CuestionarioResource($cuestionario);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\CuestionarioRequest  $CuestionarioRequest
     * @param  Cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function update(CuestionarioRequest $request, $cuestionario)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $cuestionario->update($datos);
            $modelo = new CuestionarioResource($cuestionario->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de Cuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Cuestionario  $cuestionario
     * @return \Illuminate\Http\Response
     */
    public function destroy($cuestionario)
    {
        try {
            DB::beginTransaction();
            $cuestionario->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de Cuestionario' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function imprimirCuestionario()
    {
        $empleados = Empleado::habilitado()
        ->where('salario', '!=', 0)
        ->orderBy('apellidos', 'asc')
        ->with('canton', 'area')
        ->get();
        $preguntas = Pregunta::all(['id', 'pregunta', 'codigo']);
        $reportes_empaquetado = RespuestaCuestionarioEmpleado::empaquetar($empleados);
        $reporte = compact('preguntas', 'reportes_empaquetado');
        return CuestionarioPisicosocialService::imprimir_respuesta_cuestionario($reporte);

    }

}
