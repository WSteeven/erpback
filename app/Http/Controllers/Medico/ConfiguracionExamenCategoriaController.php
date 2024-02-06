<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConfiguracionExamenCategoriaRequest;
use App\Http\Resources\Medico\ConfiguracionExamenCampoResource;
use App\Http\Resources\Medico\ConfiguracionExamenCategoriaResource;
use App\Models\Medico\ConfiguracionExamenCampo;
use App\Models\Medico\ConfiguracionExamenCategoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConfiguracionExamenCategoriaController extends Controller
{
    private $entidad = 'Configuracion de Examen Categoria';

    public function __construct()
    {
        $this->middleware('can:puede.ver.configuraciones_examenes_categorias')->only('index', 'show');
        $this->middleware('can:puede.crear.configuraciones_examenes_categorias')->only('store');
        $this->middleware('can:puede.editar.configuraciones_examenes_categorias')->only('update');
        $this->middleware('can:puede.eliminar.configuraciones_examenes_categorias')->only('destroy');
    }

    private function obtenerCategoriaCampos()
    {
        $examen_id = request('examen_id');

        $categorias = ConfiguracionExamenCategoria::ignoreRequest(['con_campos'])->filter()->where('examen_id', $examen_id)->get();

        $data = $categorias->map(fn ($categoria) => [
            'categoria' => $categoria->nombre,
            'categoria_id' => $categoria->id,
            'campos' => ConfiguracionExamenCampoResource::collection(ConfiguracionExamenCampo::where('configuracion_examen_categoria_id', $categoria->id)->get()),
        ]);

        return $data;
    }

    private function listar()
    {
        $con_campos = request('con_campos');
        // Log::channel('testing')->info('Log', ['Empleado', 'listar...']);

        if ($con_campos) return $this->obtenerCategoriaCampos();
        else return ConfiguracionExamenCampo::ignoreRequest(['campos'])->filter()->get();
    }

    public function index()
    {
        $results = $this->listar();
        return response()->json(compact('results'));
    }

    public function store(ConfiguracionExamenCategoriaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $configuracion_examen_categoria = ConfiguracionExamenCategoria::create($datos);
            $modelo = new ConfiguracionExamenCategoriaResource($configuracion_examen_categoria);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de configuracion examen categoria' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ConfiguracionExamenCategoria $configuracion_examen_categoria)
    {
        $modelo = new ConfiguracionExamenCategoriaResource($configuracion_examen_categoria);
        return response()->json(compact('modelo'));
    }


    public function update(ConfiguracionExamenCategoriaRequest $request, ConfiguracionExamenCategoria $configuracion_examen_categoria)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $configuracion_examen_categoria->update($datos);
            $modelo = new ConfiguracionExamenCategoriaResource($configuracion_examen_categoria->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de configuracion examen categoria' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ConfiguracionExamenCategoriaRequest $request, ConfiguracionExamenCategoria $configuracion_examen_categoria)
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
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de configuracion examen categoria' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
