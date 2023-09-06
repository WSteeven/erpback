<?php

namespace App\Http\Controllers;

use App\Events\ComprasProveedores\CalificacionProveedorEvent;
use App\Http\Requests\ComprasProveedores\ProveedorRequest;
use App\Http\Resources\ComprasProveedores\ProveedorResource;
use App\Models\Departamento;
use App\Models\Proveedor;
use App\Models\User;
use Exception;
use Hamcrest\Type\IsInteger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class ProveedorController extends Controller
{
    private $entidad = 'Proveedor';
    public function __construct()
    {
        $this->middleware('can:puede.ver.proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.proveedores')->only('store');
        $this->middleware('can:puede.editar.proveedores')->only('update');
        $this->middleware('can:puede.eliminar.proveedores')->only('destroy');
    }
    /**
     * Listar
     */
    public function index()
    {
        $results = ProveedorResource::collection(Proveedor::filter()->get());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(ProveedorRequest $request)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        $departamento_financiero = Departamento::where('nombre', 'FINANCIERO')->first();
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            //Respuesta
            $proveedor = Proveedor::create($datos);
            $proveedor->servicios_ofertados()->attach($request->tipos_ofrece);
            $proveedor->categorias_ofertadas()->attach($datos['categorias_ofrece']);
            $proveedor->departamentos_califican()->sync($request->departamentos);
            if (is_int($request->departamentos)) {
                if ($departamento_financiero->id != $request->departamentos)
                    $proveedor->departamentos_califican()->attach($departamento_financiero->id);
            } else {
                if (!in_array($departamento_financiero->id, $request->departamentos)) {
                    $proveedor->departamentos_califican()->attach($departamento_financiero->id);
                }
            }
            $modelo = new ProveedorResource($proveedor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();

            Log::channel('testing')->info('Log', ['Modelo a recorrer', $proveedor->departamentos_califican]);
            foreach ($proveedor->departamentos_califican as $departamento) {
                event(new CalificacionProveedorEvent($proveedor, auth()->user()->empleado->id, $departamento['responsable_id'], false));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje = '(' . $e->getLine() . ') Hubo un erorr: ' . $e->getMessage();
            return response()->json(compact('mensaje'), 500);
            //throw $th;
        }
    }


    /**
     * Consultar
     */
    public function show(Proveedor $proveedor)
    {
        $modelo = new ProveedorResource($proveedor);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ProveedorRequest $request, Proveedor  $proveedor)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        $departamento_financiero = Departamento::where('nombre', 'FINANCIERO')->first();
        try {
            DB::beginTransaction();
            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];

            //Respuesta
            $proveedor->update($datos);

            //attaching related models
            $proveedor->servicios_ofertados()->sync($request->tipos_ofrece);
            $proveedor->categorias_ofertadas()->sync($request->categorias_ofrece);
            $proveedor->departamentos_califican()->sync($request->departamentos);
            if (!in_array($departamento_financiero->id, $request->departamentos)) {
                $proveedor->departamentos_califican()->attach($departamento_financiero->id);
            }
            $modelo = new ProveedorResource($proveedor->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error:', $e->getLine(), $e->getMessage()]);
            return response()->json(['mensaje' => $e->getMessage() . '. ' . $e->getLine()], 422);
        }
    }


    /**
     * Eliminar
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
