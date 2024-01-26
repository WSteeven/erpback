<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CategoriaExamenRequest;
use App\Http\Resources\Medico\CategoriaExamenResource;
use App\Models\Medico\CategoriaExamen;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class CategoriaExamenController extends Controller
{
    private $entidad = 'Categoria de examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias_examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias_examenes')->only('store');
        $this->middleware('can:puede.editar.categorias_examenes')->only('update');
        $this->middleware('can:puede.eliminar.categorias_examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = CategoriaExamen::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(CategoriaExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $categoria_examen = CategoriaExamen::create($datos);
            $modelo = new CategoriaExamenResource($categoria_examen);
            $this->tabla_roles($categoria_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(CategoriaExamenRequest $request, CategoriaExamen $categoria_examen)
    {
        $modelo = new CategoriaExamenResource($categoria_examen);
        return response()->json(compact('modelo'));
    }


    public function update(CategoriaExamenRequest $request, CategoriaExamen $categoria_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $categoria_examen->update($datos);
            $modelo = new CategoriaExamenResource($categoria_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(CategoriaExamenRequest $request, CategoriaExamen $categoria_examen)
    {
        try {
            DB::beginTransaction();
            $categoria_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
