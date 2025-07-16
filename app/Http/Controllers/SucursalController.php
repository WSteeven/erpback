<?php

namespace App\Http\Controllers;

use App\Http\Requests\SucursalRequest;
use App\Http\Resources\SucursalResource;
use App\Models\Inventario;
use App\Models\Sucursal;
use DB;
use Exception;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;
use Src\Shared\Utils;

class SucursalController extends Controller
{
    private string $entidad = 'Sucursal';

    public function __construct()
    {
        $this->middleware('can:puede.ver.sucursales')->only('index', 'show');
        $this->middleware('can:puede.crear.sucursales')->only('store');
        $this->middleware('can:puede.editar.sucursales')->only('update');
        $this->middleware('can:puede.eliminar.sucursales')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $search = $request['search'];
        if ($request['campos']) {
            $results = Sucursal::ignoreRequest(['campos'])->filter()->orderBy('created_at', 'desc')->get($campos);
            return response()->json(compact('results'));
        } else if ($page) {
            $results = Sucursal::simplePaginate($request['offset']);
            // SucursalResource::collection($results);
            // $results->appends(['offset' => $request['offset']]);
        } else {
            $results = Sucursal::filter()->orderBy('created_at', 'desc')->get();
            // SucursalResource::collection($results);
        }
        if ($search) {
            $sucursal = Sucursal::select('id')->where('lugar', 'LIKE', '%' . $search . '%')->first();

            if ($sucursal) $results = SucursalResource::collection(Sucursal::where('id', $sucursal->id)->get());
        }
        $results = SucursalResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(SucursalRequest $request)
    {
        //Adaptación de foreign keys
        $datos = $request->validated();
        $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        $sucursal = Sucursal::create($datos);
        $modelo = new SucursalResource($sucursal);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Sucursal $sucursal)
    {
        $modelo = new SucursalResource($sucursal);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function update(SucursalRequest $request, Sucursal $sucursal)
    {
        try {

            // Log::channel('testing')->info('Log', ['Datos recibidos', $request->all()]);

            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

            // Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            // $datos['administrador_id'] = $request->safe()->only(['administrador'])['administrador'];

            // Verificar que la bodega esté vacia para inactivarla
            if (!$datos['activo']) {
                $itemsInventario = Inventario::where('sucursal_id', $sucursal->id)->where('cantidad', '>', 0)->count();
                if ($itemsInventario > 0) throw new Exception('No se puede inactivar esta sucursal porque aún existe material cargado en inventario. Por favor todo el inventario de esta bodega debe estar en cero para poder desactivarla');
            }

            DB::beginTransaction();
            //Respuesta
            $sucursal->update($datos);
            $modelo = new SucursalResource($sucursal->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $ex) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
    }

    /**
     * Eliminar
     */
    public function destroy(Sucursal $sucursal)
    {
        $sucursal->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
