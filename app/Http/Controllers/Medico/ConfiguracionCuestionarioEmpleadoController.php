<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConfiguracionCuestionarioEmpleadoRequest;
use App\Http\Resources\Medico\ConfiguracionCuestionarioEmpleadoResource;
use App\Models\Medico\ConfiguracionCuestionarioEmpleado;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConfiguracionCuestionarioEmpleadoController extends Controller
{
    private $entidad = 'CONFIGURACIONCUESTIONARIOEMPLEADO';

    public function __construct()
    {
        $this->middleware('can:puede.ver.configuraciones_cuestionarios_empleados')->only('index', 'show');
        $this->middleware('can:puede.crear.configuraciones_cuestionarios_empleados')->only('store');
        $this->middleware('can:puede.editar.configuraciones_cuestionarios_empleados')->only('update');
        $this->middleware('can:puede.eliminar.configuraciones_cuestionarios_empleados')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = ConfiguracionCuestionarioEmpleado::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ConfiguracionCuestionarioEmpleadoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfiguracionCuestionarioEmpleadoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $configuracioncuestionarioempleado = ConfiguracionCuestionarioEmpleado::create($datos);
            $modelo = new ConfiguracionCuestionarioEmpleadoResource($configuracioncuestionarioempleado);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  ConfiguracionCuestionarioEmpleado  $config_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function show(ConfiguracionCuestionarioEmpleado $config_cuestionario_empleado)
    {
        $modelo = new ConfiguracionCuestionarioEmpleadoResource($config_cuestionario_empleado);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ConfiguracionCuestionarioEmpleadoRequest  $request
     * @param  ConfiguracionCuestionarioEmpleado  $config_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function update(ConfiguracionCuestionarioEmpleadoRequest $request, ConfiguracionCuestionarioEmpleado $config_cuestionario_empleado)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $config_cuestionario_empleado->update($datos);
            $modelo = new ConfiguracionCuestionarioEmpleadoResource($config_cuestionario_empleado->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ConfiguracionCuestionarioEmpleado  $config_cuestionario_empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConfiguracionCuestionarioEmpleado $config_cuestionario_empleado)
    {
        try {
            DB::beginTransaction();
            $config_cuestionario_empleado->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }
    public function obtenerConfiguracion(){
        try {
            $results = ConfiguracionCuestionarioEmpleado::last();
        } catch (Exception $e) {
        }
    }
}
