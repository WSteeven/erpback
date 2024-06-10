<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\OrientacionSexualRequest;
use App\Http\Resources\Medico\OrientacionSexualResource;
use App\Models\Medico\OrientacionSexual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class OrientacionSexualController extends Controller
{
    private $entidad = 'Orientacion Sexual';

    public function __construct()
    {
        $this->middleware('can:puede.ver.orientaciones_sexuales')->only('index', 'show');
        $this->middleware('can:puede.crear.orientaciones_sexuales')->only('store');
        $this->middleware('can:puede.editar.orientaciones_sexuales')->only('update');
        $this->middleware('can:puede.eliminar.orientaciones_sexuales')->only('destroy');
    }

    public function index()
    {
        $results = OrientacionSexual::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(OrientacionSexualRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $orientacion_sexual = OrientacionSexual::create($datos);
            $modelo = new OrientacionSexualResource($orientacion_sexual);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de orientacion sexual' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(OrientacionSexualRequest $request, OrientacionSexual $orientacion_sexual)
    {
        $modelo = new OrientacionSexualResource($orientacion_sexual);
        return response()->json(compact('modelo'));
    }


    public function update(OrientacionSexualRequest $request, OrientacionSexual $orientacion_sexual)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $orientacion_sexual->update($datos);
            $modelo = new OrientacionSexualResource($orientacion_sexual->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de orientacion sexual' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(OrientacionSexualRequest $request, OrientacionSexual $orientacion_sexual)
    {
        try {
            DB::beginTransaction();
            $orientacion_sexual->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de orientacion sexual' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
