<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorizacionRequest;
use App\Http\Resources\AutorizacionResource;
use App\Models\Autorizacion;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class AutorizacionController extends Controller
{
    private $entidad = 'Autorizacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.autorizaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.autorizaciones')->only('store');
        $this->middleware('can:puede.editar.autorizaciones')->only('update');
        $this->middleware('can:puede.eliminar.autorizaciones')->only('update');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $campos = explode(',', $request['campos']);
        $results = [];
        $es_validado = false;
        $es_jefe_inmediato =false;
         $user =  Auth::user();
        $es_autorizador=$user->can('puede.autorizar.permiso_nomina');
        $es_administrador= $user->hasRole([User::ROL_ADMINISTRADOR]);
        if ($request->es_validado) {
            $es_validado = true;
        }
        if ($request->es_jefe_inmediato) {
            $es_jefe_inmediato =true;
        }
        if ($request['campos']) {
            if($es_jefe_inmediato){
                $results = Autorizacion::ignoreRequest(['campos', 'es_validado','es_jefe_inmediato'])->where('id',2)->filter()->get($campos);
                return response()->json(compact('results'));
            }
            if ($es_validado == false) {
                $results = Autorizacion::ignoreRequest(['campos', 'es_validado','es_jefe_inmediato'])->where('id', '!=', 4)->filter()->get($campos);
                return response()->json(compact('results'));
            }
            $results = Autorizacion::ignoreRequest(['campos', 'es_validado','es_jefe_inmediato'])->filter()->get($campos);
            return response()->json(compact('results'));
        } else
        if ($page) {
            $results = Autorizacion::simplePaginate($request['offset']);
            AutorizacionResource::collection($results);
            $results->appends(['offset' => $request['offset']]);
        } else {

            $results = Autorizacion::ignoreRequest(['campos', 'es_validado','es_jefe_inmediato'])->filter()->get();
        }
        AutorizacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(AutorizacionRequest $request)
    {

        // Respuesta
        $modelo = Autorizacion::create($request->validated());
        $modelo = new AutorizacionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Autorizacion $autorizacion)
    {
        $modelo = new AutorizacionResource($autorizacion);
        return response()->json(compact('modelo'));
    }


    public function update(AutorizacionRequest $request, Autorizacion  $autorizacion)
    {
        //Respuesta
        $autorizacion->update($request->validated());
        $modelo = new AutorizacionResource($autorizacion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Autorizacion $autorizacion)
    {
        $autorizacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
