<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ResultadoExamenPreocupacionalRequest;
use App\Http\Resources\Medico\ResultadoExamenPreocupacionalResource;
use App\Models\Medico\ResultadoExamenPreocupacional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ResultadoExamenPreocupacionalController extends Controller
{
    private $entidad = 'Examen Preocupacional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.examenes_preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.examenes_preocupacionales')->only('store');
        $this->middleware('can:puede.editar.examenes_preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.examenes_preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ResultadoExamenPreocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ResultadoExamenPreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $preocupacional = ResultadoExamenPreocupacional::create($datos);
            $modelo = new ResultadoExamenPreocupacionalResource($preocupacional);
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

    public function show(ResultadoExamenPreocupacional $preocupacional)
    {
        $modelo = new ResultadoExamenPreocupacionalResource($preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(ResultadoExamenPreocupacionalRequest $request, ResultadoExamenPreocupacional $preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $preocupacional->update($datos);
            $modelo = new ResultadoExamenPreocupacionalResource($preocupacional->refresh());
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

    public function destroy(ResultadoExamenPreocupacional $preocupacional)
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
