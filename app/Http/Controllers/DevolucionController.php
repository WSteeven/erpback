<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucionRequest;
use App\Http\Resources\DevolucionResource;
use App\Models\Devolucion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class DevolucionController extends Controller
{
    private $entidad = 'Devolución';
    public function __construct()
    {
        $this->middleware('can:puede.ver.devoluciones')->only('index', 'show');
        $this->middleware('can:puede.crear.devoluciones')->only('store');
        $this->middleware('can:puede.editar.devoluciones')->only('update');
        $this->middleware('can:puede.eliminar.devoluciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $campos = explode(',', $request['page']);
        $results = [];

        if ($request['campos']) {
            $results = Devolucion::where('estado', Devolucion::CREADA)->get($campos);
            $results = DevolucionResource::collection($results);
            return response()->json(compact('results'));
        } else 
        if ($page) {
            if(auth()->user()->hasRole(User::ROL_BODEGA)){
                $results = Devolucion::filtrarDevolucionesBodegueroConPaginacion($estado,$offset);
                DevolucionResource::collection($results);
            }else{
                $results = Devolucion::filtrarDevolucionesEmpleadoConPaginacion($estado, $offset);
                DevolucionResource::collection($results);
            }
        } else {
            $results = Devolucion::ignoreRequest(['campos'])->filter()->get();
        }

        DevolucionResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(DevolucionRequest $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida en devolucion:', $request->all()]);
        try{
            DB::beginTransaction();
            // Adaptacion de foreign keys
            $datos = $request->validated();
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            !is_null($request->tarea)??$datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
    
            // Respuesta
            $devolucion = Devolucion::create($datos);
            $modelo = new DevolucionResource($devolucion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            foreach($request->listadoProductos as $listado){
                $devolucion->detalles()->attach($listado['id'], ['cantidad'=>$listado['cantidad']]);
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR del catch', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Devolucion $devolucion)
    {
        $modelo = new DevolucionResource($devolucion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(DevolucionRequest $request, Devolucion $devolucion)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];

        // Respuesta
        $devolucion->update($datos);
        $modelo = new DevolucionResource($devolucion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Devolucion $devolucion)
    {
        $devolucion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }


    /**
     * Consultar datos sin el método show.
     */
    public function showPreview(Devolucion $devolucion){
        $modelo = new DevolucionResource($devolucion);

        return response()->json(compact('modelo'), 200);
    }

    /**
     * Anular una devolución
     */
    public function anular(Devolucion $devolucion)
    {
        $devolucion->estado = Devolucion::ANULADA;
        $devolucion->save();
    }
}
 