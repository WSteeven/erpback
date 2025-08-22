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
use Illuminate\Validation\ValidationException;
use Src\App\EmpleadoService;
use Src\Shared\Utils;
use Throwable;

class VendedorController extends Controller
{
    private string $entidad = 'Vendedor';
    private EmpleadoService $empleadoService;

    public function __construct()
    {
        $this->empleadoService = new EmpleadoService();
        $this->middleware('can:puede.ver.vendedores')->only('index', 'show');
        $this->middleware('can:puede.crear.vendedores')->only('store');
        $this->middleware('can:puede.editar.vendedores')->only('update');
        $this->middleware('can:puede.eliminar.vendedores')->only('destroy');
    }

    public function index()
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

    /**
     * @throws Throwable
     */
    public function store(VendedorRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $vendedor = Vendedor::create($datos);
            // Log::channel('testing')->info('Log', ['vendedor creado: ', $vendedor]);
            $modelo = new VendedorResource($vendedor);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(Vendedor $vendedor)
    {
        $modelo = new VendedorResource($vendedor);
        return response()->json(compact('modelo'));
    }

    /**
     * @throws Throwable
     */
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

    /**
     * @throws ValidationException
     */
    public function destroy()
//    public function destroy(Vendedor $vendedor)
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);

//        $vendedor->delete();
//        return response()->json(compact('vendedor'));
    }

    /**
     * Desactivar un vendedor
     * @throws ValidationException
     * @throws Throwable
     */
    public function desactivar(Request $request, Vendedor $vendedor)
    {
        try {
            DB::beginTransaction();
            $request->validate(['causa_desactivacion' => ['required', 'string']]);
            $vendedor->causa_desactivacion = $request->causa_desactivacion;
            // Si como empleado esta inactivo, no se puede activar antes de que se active el empleado.
            if (!$vendedor->empleado->estado) throw new Exception('El empleado esta inactivo. Por favor contacte a RRHH para que active el empleado y pueda continuar activandolo como vendedor.');
            $vendedor->activo = !$vendedor->activo;
            $vendedor->save();
            DB::commit();
            $modelo = new VendedorResource($vendedor->refresh());
            return response()->json(compact('modelo'));
        } catch (Exception $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    public function desactivarMasivo()
    {
        $this->empleadoService->desactivarMasivoVendedoresClaro();
        $mensaje = 'Vendedores actualizados satisfactoriamente';
        return response()->json(compact('mensaje'));
    }
}
