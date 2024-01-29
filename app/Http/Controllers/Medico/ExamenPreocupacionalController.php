<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ExamenPreocupacionalRequest;
use App\Http\Resources\Medico\ExamenPreocupacionalResource;
use App\Models\Medico\ExamenPreocupacional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ExamenExamenPreocupacionalController extends Controller
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
        $results = ExamenPreocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ExamenPreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $preocupacional = ExamenPreocupacional::create($datos);
            $modelo = new ExamenPreocupacionalResource($preocupacional);
            $this->tabla_roles($preocupacional);
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

    public function show(ExamenPreocupacionalRequest $request, ExamenPreocupacional $preocupacional)
    {
        $modelo = new ExamenPreocupacionalResource($preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(ExamenPreocupacionalRequest $request, ExamenPreocupacional $preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $preocupacional->update($datos);
            $modelo = new ExamenPreocupacionalResource($preocupacional->refresh());
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

    public function destroy(ExamenPreocupacionalRequest $request, ExamenPreocupacional $preocupacional)
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
