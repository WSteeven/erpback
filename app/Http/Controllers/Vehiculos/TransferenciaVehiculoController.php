<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\TransferenciaVehiculoRequest;
use App\Http\Resources\Vehiculos\TransferenciaVehiculoResource;
use App\Models\User;
use App\Models\Vehiculos\AsignacionVehiculo;
use App\Models\Vehiculos\TransferenciaVehiculo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class TransferenciaVehiculoController extends Controller
{
    private $entidad = 'Transferencia de Vehículo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transferencias_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.transferencias_vehiculos')->only('store');
        $this->middleware('can:puede.editar.transferencias_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.transferencias_vehiculos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR_VEHICULOS])) {
            $results = TransferenciaVehiculo::ignoreRequest(['entrega_id', 'responsable_id'])->filter()->orderBy('id', 'desc')->get();
        } else {
            $results = TransferenciaVehiculo::where(function ($query) {
                $query->where('entrega_id', auth()->user()->empleado->id)
                    ->orWhere('responsable_id', auth()->user()->empleado->id);
            })->ignoreRequest(['entrega_id', 'responsable_id'])->filter()->orderBy('id', 'desc')->get();
        }

        $results = TransferenciaVehiculoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferenciaVehiculoRequest $request)
    {
        $datos = $request->validated();
        try {
            $vehiculoAsignado = TransferenciaVehiculo::where('vehiculo_id', $datos['vehiculo_id'])
                ->where('estado', AsignacionVehiculo::ACEPTADO)
                ->where('devuelto', false)->orderBy('id', 'desc')->first();
            if ($vehiculoAsignado) throw new Exception('El vehículo seleccionado ya está asignado a un chofer y aún no ha sido devuelto, por favor devuelve el vehículo para poder asignarlo nuevamente.');
            DB::beginTransaction();
            $transferencia = TransferenciaVehiculo::create($datos);
            //Lanzar el evento de la notificación
            // event(new NotificarTransferenciaVehiculoEvent($asignacion));
            $modelo = new TransferenciaVehiculoResource($transferencia);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'No se puedo guardar: ');
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TransferenciaVehiculo $transferencia)
    {
        $modelo = new TransferenciaVehiculoResource($transferencia);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransferenciaVehiculoRequest $request, TransferenciaVehiculo $transferencia)
    {
        $datos = $request->validated();
        try {
            DB::beginTransaction();

            //Respuesta
            $transferencia->update($datos);
            $modelo = new TransferenciaVehiculoResource($transferencia->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            //Marcar como leída la notificación anterior y lanzar el evento de la notificación
            $transferencia->latestNotificacion()->update(['leida' => true]);
            // if ($transferencia->estado != 'ANULADO')
            // event(new NotificarAsignacionVehiculoEvent($asignacion));

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th, 'No se puedo actualizar el registro: ');
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransferenciaVehiculo $transferencia)
    {
        $transferencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
