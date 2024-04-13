<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CategoriaExamenFisicoRequest;
use App\Http\Resources\Medico\CategoriaExamenFisicoResource;
use App\Models\Medico\CategoriaExamenFisico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class CategoriaExamenFisicoController extends Controller
{
    private $entidad = 'Categoria de examen fisico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.categorias_examenes_fisicos')->only('index', 'show');
        $this->middleware('can:puede.crear.categorias_examenes_fisicos')->only('store');
        $this->middleware('can:puede.editar.categorias_examenes_fisicos')->only('update');
        $this->middleware('can:puede.eliminar.categorias_examenes_fisicos')->only('destroy');
    }

    public function index()
    {
        $results = CategoriaExamenFisico::ignoreRequest(['campos'])->filter()->get();
        $results = CategoriaExamenFisicoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(CategoriaExamenFisicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $categoria_examen_fisico = CategoriaExamenFisico::create($datos);
            $modelo = new CategoriaExamenFisicoResource($categoria_examen_fisico);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro decategoria examen fisico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(CategoriaExamenFisicoRequest $request, CategoriaExamenFisico $categoria_examen_fisico)
    {
        $modelo = new CategoriaExamenFisicoResource($categoria_examen_fisico);
        return response()->json(compact('modelo'));
    }


    public function update(CategoriaExamenFisicoRequest $request, CategoriaExamenFisico $categoria_examen_fisico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $categoria_examen_fisico->update($datos);
            $modelo = new CategoriaExamenFisicoResource($categoria_examen_fisico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro decategoria examen fisico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(CategoriaExamenFisicoRequest $request, CategoriaExamenFisico $categoria_examen_fisico)
    {
        try {
            DB::beginTransaction();
            $categoria_examen_fisico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro decategoria examen fisico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
