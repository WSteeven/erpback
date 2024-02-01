<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenRequest;
use App\Http\Resources\Medico\ExamenResource;
use App\Models\Medico\Examen;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\App\Medico\ExamenService;
use Src\Shared\Utils;

class ExamenController extends Controller
{
    private $entidad = 'Examen';
    private $examenService;

    public function __construct()
    {
        $this->middleware('can:puede.ver.examenes')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes')->only('store');
        $this->middleware('can:puede.editar.examenes')->only('update');
        $this->middleware('can:puede.eliminar.examenes')->only('destroy');
        $this->examenService = new ExamenService();
    }

    public function index()
    {
        $results = $this->examenService->listar();
        $results = ExamenResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ExamenRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $examen = Examen::create($datos);
            $modelo = new ExamenResource($examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ExamenRequest $request, Examen $examen)
    {
        $modelo = new ExamenResource($examen);
        return response()->json(compact('modelo'));
    }


    public function update(ExamenRequest $request, Examen $examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $examen->update($datos);
            $modelo = new ExamenResource($examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ExamenRequest $request, Examen $examen)
    {
        try {
            DB::beginTransaction();
            $examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
