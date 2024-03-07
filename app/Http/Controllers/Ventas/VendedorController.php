<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\VendedorRequest;
use App\Http\Resources\Ventas\VendedorResource;
use App\Models\User;
use App\Models\Ventas\Vendedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class VendedorController extends Controller
{
    private $entidad = 'Vendedor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.vendedores')->only('index', 'show');
        $this->middleware('can:puede.crear.vendedores')->only('store');
        $this->middleware('can:puede.editar.vendedores')->only('update');
        $this->middleware('can:puede.eliminar.vendedores')->only('destroy');
    }
    public function index(Request $request)
    {
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        if (auth()->user()->hasRole([User::SUPERVISOR_VENTAS])) {
            $results = Vendedor::where('jefe_inmediato_id', auth()->user()->empleado->id)->filter()->get();
        } else {
            $results = Vendedor::ignoreRequest(['campos'])->filter()->get($campos);
        }

        $results = VendedorResource::collection($results);
        return response()->json(compact('results'));
    }
    public function store(VendedorRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $vendedor = Vendedor::create($datos);
            $modelo = new VendedorResource($vendedor);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(Request $request, Vendedor $vendedor)
    {
        $modelo = new VendedorResource($vendedor);
        return response()->json(compact('modelo'));
    }

    public function update(VendedorRequest $request, Vendedor $vendedor)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $vendedor->update($datos);
            $modelo = new VendedorResource($vendedor->refresh());
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
    public function destroy(Request $request, Vendedor $vendedor)
    {
        $vendedor->delete();
        return response()->json(compact('vendedor'));
    }

    /**
     * Desactivar un vendedor
     */
    public function desactivar(Request $request, Vendedor $vendedor)
    {
        $request->validate(['causa_desactivacion' => ['required', 'string']]);
        $vendedor->causa_desactivacion = $request->causa_desactivacion;
        $vendedor->activo = !$vendedor->activo;
        $vendedor->save();

        $modelo = new VendedorResource($vendedor->refresh());
        return response()->json(compact('modelo'));
    }
}
