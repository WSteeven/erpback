<?php

namespace App\Http\Controllers\RecursosHumanos;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\RubroRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RubroResource;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RubroController extends Controller
{
    private $entidad = 'Rubro';

    public function __construct()
    {
        $this->middleware('can:puede.ver.rubro')->only('index', 'show');
        $this->middleware('can:puede.crear.rubro')->only('store');
        $this->middleware('can:puede.editar.rubro')->only('update');
        $this->middleware('can:puede.eliminar.rubro')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = Rubros::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function sueldo_basico()
    {
        $rubro = Rubros::where('nombre_rubro', 'Sueldo Basico')->first();
        return response()->json(compact('rubro'));
    }
    public function porcentaje_iess()
    {
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        return response()->json(compact('porcentaje_iess'));
    }
    public function porcentaje_anticipo()
    {
        $rubro = Rubros::find(4) != null ? Rubros::find(4) : 0;
        return response()->json(compact('rubro'));
    }
    public function store(RubroRequest $request)
    {
        try {
            DB::beginTransaction();
            $rubro = Rubros::create($request->validated());
            $modelo = new RubroResource($rubro);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al aprobar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(Request $request, Rubros $rubro)
    {
        $modelo = $rubro;
        return response()->json(compact('modelo'));
    }


    public function update(RubroRequest $request, Rubros $rubro)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $rubro->update($datos);
            $modelo = new RubroResource($rubro->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy( Rubros $rubro)
    {
        $rubro->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
