<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificacionRequest;
use App\Http\Resources\NotificacionResource;
use App\Models\Notificacion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class NotificacionController extends Controller
{
    private $entidad = 'Notificacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.notificaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.notificaciones')->only('store');
        $this->middleware('can:puede.editar.notificaciones')->only('update');
        $this->middleware('can:puede.eliminar.notificaciones')->only('destroy');
    }

    /**
     * Listar
     */
    public function index(Request $request)
    {
        $campos = explode(',', $request['campos']);

        if ($request['campos']) {
            if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                $results = Notificacion::ignoreRequest(['campos'])->where('mensaje', 'LIKE', '%pedido recién autorizado en la sucursal%')->orWhere('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->limit(10)->get($campos);
            } else {
                $results = Notificacion::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->limit(10)->get($campos);
            }
        } else {
            if (auth()->user()->hasRole(User::ROL_BODEGA)) $results = Notificacion::where('mensaje', 'LIKE', '%pedido recién autorizado en la sucursal%')->filter()->orderBy('id', 'desc')->get();
            else $results = Notificacion::where('per_destinatario_id', auth()->user()->empleado->id)->filter()->orderBy('id', 'desc')->get();
        }


        return response()->json(compact('results'));
    }
    /**
     * Guardar
     */
    public function store(NotificacionRequest $request)
    {
        //Respuesta
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['per_originador_id'] = $request->safe()->only(['per_originador'])['per_originador'];
            $datos['per_destinatario_id'] = $request->safe()->only(['per_destinatario'])['per_destinatario'];

            $notificacion = Notificacion::create($datos);
            DB::commit();
            $modelo = new NotificacionResource($notificacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la notificacion', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . $e->getLine()], 422);
        }
    }
    /**
     * Consultar
     */
    public function show(Notificacion $notificacion)
    {
        $modelo = new NotificacionResource($notificacion);
        return response()->json(compact('modelo'));
    }
    /**
     * Actualizar
     */
    public function update(NotificacionRequest $request, Notificacion $notificacion)
    {
        //En esta parte se hace la actualización de la notificacion de leída=false a leída=true
        $notificacion->leida = true; //se marca como leída
        $notificacion->save(); //se guarda la notificacion actualizada

        $modelo = new NotificacionResource($notificacion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Eliminar
     */
    public function destroy(Notificacion $notificacion)
    {
        //Segun la logica de negocio no se deberían eliminar las notificaciones.
        $notificacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Marcar como leída una notificacion
     */
    public function leida(Notificacion $notificacion)
    {
        $notificacion->leida = true;
        $notificacion->save();
        $modelo = $notificacion;

        return response()->json(compact('modelo'), 200);
    }
}
