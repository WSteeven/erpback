<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\PreocupacionalRequest;
use App\Http\Resources\Medico\PreocupacionalResource;
use App\Models\Medico\Preocupacional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class PreocupacionalController extends Controller
{
    private $entidad = 'Preocupacional';

    public function __construct()
    {
        $this->middleware('can:puede.ver.preocupacionales')->only('index', 'show');
        $this->middleware('can:puede.crear.preocupacionales')->only('store');
        $this->middleware('can:puede.editar.preocupacionales')->only('update');
        $this->middleware('can:puede.eliminar.preocupacionales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Preocupacional::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(PreocupacionalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $preocupacional = Preocupacional::create($datos);
            $modelo = new PreocupacionalResource($preocupacional);
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

    public function show(PreocupacionalRequest $request, Preocupacional $preocupacional)
    {
        $modelo = new PreocupacionalResource($preocupacional);
        return response()->json(compact('modelo'));
    }


    public function update(PreocupacionalRequest $request, Preocupacional $preocupacional)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $preocupacional->update($datos);
            $modelo = new PreocupacionalResource($preocupacional->refresh());
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

    public function destroy(PreocupacionalRequest $request, Preocupacional $preocupacional)
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
