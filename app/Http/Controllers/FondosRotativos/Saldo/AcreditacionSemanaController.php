<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\Saldo\AcreditacionSemanaRequest;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionSemanaResource;
use App\Models\FondosRotativos\Saldo\AcreditacionSemana;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class AcreditacionSemanaController extends Controller
{
    private $entidad = 'Acreditacion Semanal';
    public function __construct()
    {
        $this->middleware('can:puede.ver.acreditacion_semana')->only('index', 'show');
        $this->middleware('can:puede.crear.acreditacion_semana')->only('store');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = AcreditacionSemana::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }
    public function show(Request $request, AcreditacionSemana $descuentos_generales)
    {
        return response()->json(compact('descuentos_generales'));
    }
    public function store(AcreditacionSemanaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $acreditacionsemana = AcreditacionSemana::create($datos);
            $modelo = new AcreditacionSemanaResource($acreditacionsemana);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(AcreditacionSemanaRequest $request, AcreditacionSemana $acreditacionsemana)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $acreditacionsemana->update($datos);
            $modelo = new AcreditacionSemanaResource($acreditacionsemana->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, AcreditacionSemana $acreditacionsemana)
    {
        $acreditacionsemana->delete();
        return response()->json(compact('acreditacionsemana'));
    }
}
