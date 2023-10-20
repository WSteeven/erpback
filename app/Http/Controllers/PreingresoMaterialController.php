<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreingresoMaterialRequest;
use App\Http\Resources\PreingresoMaterialResource;
use App\Models\Pedido;
use App\Models\PreingresoMaterial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class PreingresoMaterialController extends Controller
{
    private $entidad = 'Preingreso de Material';
    public function __construct()
    {
        $this->middleware('can:puede.ver.preingresos_materiales')->only('index', 'show');
        $this->middleware('can:puede.crear.preingresos_materiales')->only('store');
        $this->middleware('can:puede.editar.preingresos_materiales')->only('update');
        $this->middleware('can:puede.eliminar.preingresos_materiales')->only('destroy');
    }


    /**
     * Listar
     */
    public function index()
    {
        $results = [];

        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(PreingresoMaterialRequest $request)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            //Adaptacion de foreign keys
            $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];
            $datos['autorizador_id'] = $request->safe()->only(['autorizador'])['autorizador'];
            $datos['responsable_id'] = $request->responsable_id;
            $datos['coordinador_id'] = $request->safe()->only(['coordinador'])['coordinador'];
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            if ($request->tarea) $datos['tarea_id'] = $request->safe()->only(['tarea'])['tarea'];

            Log::channel('testing')->info('Log', ['Datos validados y casteados:', $datos]);
            //Se crea el registro de preingreso
            $preingreso = PreingresoMaterial::create($datos);

            //Se crea los detalles y se almacena en detalles productos
            PreingresoMaterial::almacenarDetalles($preingreso, $request->listadoProductos);


            //Respuesta
            $modelo = new PreingresoMaterialResource($preingreso);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro', "excepción" => $e->getMessage()], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }
}
