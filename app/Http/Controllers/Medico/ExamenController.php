<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenRequest;
use App\Http\Resources\Medico\ExamenResource;
use App\Models\Medico\Examen;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ExamenController extends Controller
{
    private $entidad = 'Examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes')->only('store');
        $this->middleware('can:puede.editar.examenes')->only('update');
        $this->middleware('can:puede.eliminar.examenes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Examen::ignoreRequest(['campos'])->filter()->get();
        $results = ExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $categoria_examen = Examen::create($datos);
            $modelo = new ExamenResource($categoria_examen);
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

    public function show(ExamenRequest $request, Examen $categoria_examen)
    {
        $modelo = new ExamenResource($categoria_examen);
        return response()->json(compact('modelo'));
    }


    public function update(ExamenRequest $request, Examen $categoria_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $categoria_examen->update($datos);
            $modelo = new ExamenResource($categoria_examen->refresh());
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

    public function destroy(ExamenRequest $request, Examen $categoria_examen)
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
