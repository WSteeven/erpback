<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConfiguracionExamenCampoRequest;
use App\Http\Resources\Medico\ConfiguracionExamenCampoResource;
use App\Models\Medico\ConfiguracionExamenCampo;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ConfiguracionExamenCampoController extends Controller
{
    private $entidad = 'Configuracion de Examen Campo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.configuraciones_examenes_campos')->only('index', 'show');
        $this->middleware('can:puede.crear.configuraciones_examenes_campos')->only('store');
        $this->middleware('can:puede.editar.configuraciones_examenes_campos')->only('update');
        $this->middleware('can:puede.eliminar.configuraciones_examenes_campos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ConfiguracionExamenCampo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ConfiguracionExamenCampoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $configuracion_examen_categoria = ConfiguracionExamenCampo::create($datos);
            $modelo = new ConfiguracionExamenCampoResource($configuracion_examen_categoria);
            $this->tabla_roles($configuracion_examen_categoria);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de configuracion de examen campo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ConfiguracionExamenCampoRequest $request, ConfiguracionExamenCampo $configuracion_examen_categoria)
    {
        $modelo = new ConfiguracionExamenCampoResource($configuracion_examen_categoria);
        return response()->json(compact('modelo'));
    }


    public function update(ConfiguracionExamenCampoRequest $request, ConfiguracionExamenCampo $configuracion_examen_categoria)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $configuracion_examen_categoria->update($datos);
            $modelo = new ConfiguracionExamenCampoResource($configuracion_examen_categoria->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de configuracion de examen campo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ConfiguracionExamenCampoRequest $request, ConfiguracionExamenCampo $configuracion_examen_categoria)
    {
        try {
            DB::beginTransaction();
            $configuracion_examen_categoria->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de configuracion de examen campo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
