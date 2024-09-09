<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConstanteVitalRequest;
use App\Http\Resources\Medico\ConstanteVitalResource;
use App\Models\Medico\ConstanteVital;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConstanteVitalController extends Controller
{
    private $entidad = 'Constante Vital';

    public function __construct()
    {
        $this->middleware('can:puede.ver.constantes_vitales')->only('index', 'show');
        $this->middleware('can:puede.crear.constantes_vitales')->only('store');
        $this->middleware('can:puede.editar.constantes_vitales')->only('update');
        $this->middleware('can:puede.eliminar.constantes_vitales')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = ConstanteVital::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(ConstanteVitalRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $constante_vital = ConstanteVital::create($datos);
            $modelo = new ConstanteVitalResource($constante_vital);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el constante vital' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(ConstanteVitalRequest $request, ConstanteVital $constante_vital)
    {
        $modelo = new ConstanteVitalResource($constante_vital);
        return response()->json(compact('modelo'));
    }


    public function update(ConstanteVitalRequest $request, ConstanteVital $constante_vital)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $constante_vital->update($datos);
            $modelo = new ConstanteVitalResource($constante_vital->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el constante vital' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(ConstanteVitalRequest $request, ConstanteVital $constante_vital)
    {
        try {
            DB::beginTransaction();
            $constante_vital->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el constante vital' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
