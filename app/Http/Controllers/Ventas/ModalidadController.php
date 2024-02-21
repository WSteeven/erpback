<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\ModalidadRequest;
use App\Http\Resources\Ventas\ModalidadResource;
use App\Models\Ventas\Modalidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class ModalidadController extends Controller
{
    private $entidad = 'Modalidad';
    public function __construct()
    {
        $this->middleware('can:puede.ver.modalidades')->only('index', 'show');
        $this->middleware('can:puede.crear.modalidades')->only('store');
        $this->middleware('can:puede.editar.modalidades')->only('update');
        $this->middleware('can:puede.eliminar.modalidades')->only('destroy');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = Modalidad::ignoreRequest(['campos'])->filter()->get();
        $results = ModalidadResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, Modalidad $modalidad)
    {
        $modelo = new ModalidadResource($modalidad);

        return response()->json(compact('modelo'));
    }
    public function store(ModalidadRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $modalidad = Modalidad::create($datos);
            $modelo = new ModalidadResource($modalidad);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function update(ModalidadRequest $request, Modalidad $modalidad)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $modalidad->update($datos);
            $modelo = new ModalidadResource($modalidad->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Modalidad $modalidad)
    {
        $modalidad->delete();
        return response()->json(compact('modalidad'));
    }
}
