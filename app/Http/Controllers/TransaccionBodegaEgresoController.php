<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaccionBodegaRequest;
use App\Http\Resources\TransaccionBodegaResource;
use App\Models\TransaccionBodega;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class TransaccionBodegaEgresoController extends Controller
{
    private $entidad = 'Transacción';

    /**
     * Listar
     */
    public function index()
    {
        $results = [];
        if (auth()->user()->hasRole([User::ROL_COORDINADOR, User::ROL_BODEGA])) {
            $transacciones = TransaccionBodega::all();
        } else {
            $transacciones = TransaccionBodega::where('solicitante_id', auth()->user()->empleado->id)->get();
        }

        foreach ($transacciones as $transaccion) {
            if ($transaccion->subtipo->tipoTransaccion->tipo === 'EGRESO') {
                array_push($results, $transaccion);
            }
        }

        $results = TransaccionBodegaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(TransaccionBodegaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
            $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
            $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
            $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
            if ($request->per_atiende) {
                $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
            }
            //datos de las relaciones muchos a muchos
            $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
            $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

            //Creacion de la transaccion
            $transaccion = TransaccionBodega::create($datos);


            //Guardar la autorizacion con su observacion
            if ($request->observacion_aut) {
                $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
            } else {
                $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
            }

            //Guardar el estado con su observacion
            if ($request->observacion_est) {
                $transaccion->estados()->attach($datos['estado_id'], ['observacion' => $datos['observacion_est']]);
            } else {
                $transaccion->estados()->attach($datos['estado_id']);
            }
            //Guardar los productos seleccionados
            foreach ($request->listadoProductosSeleccionados as $listado) {
                $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
            }

            DB::commit(); //Se registra la transaccion y sus detalles exitosamente

            $modelo = new TransaccionBodegaResource($transaccion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de la transaccion de ingreso', $e->getMessage()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(TransaccionBodega $transaccion)
    {
        // Log::channel('testing')->info('Log', ['Transaccion en el show de ingreso', $transaccion]);
        $modelo = new TransaccionBodegaResource($transaccion);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(TransaccionBodegaRequest $request, TransaccionBodega $transaccion)
    {
        $datos = $request->validated();
        $datos['subtipo_id'] = $request->safe()->only(['subtipo'])['subtipo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        if ($request->per_atiende) {
            $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
        }
        //datos de las relaciones muchos a muchos
        $datos['autorizacion_id'] = $request->safe()->only(['autorizacion'])['autorizacion'];
        $datos['estado_id'] = $request->safe()->only(['estado'])['estado'];

        if ($transaccion->solicitante->id === auth()->user()->empleado->id) {
            try {
                DB::beginTransaction();
                $transaccion->update($datos); //Actualizacion de la transacción
                $transaccion->detalles()->detach(); //Borra los registros de la tabla intermedia para guardar los modificados
                foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                    $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                }

                DB::commit();

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro'], 422);
            }

            return response()->json(compact('mensaje', 'modelo'));
        } else {
            //Aquí el coordinador o jefe inmediato autoriza la transaccion de sus subordinados y modifica los datos del listado
            if ($transaccion->per_autoriza_id === auth()->user()->empleado->id) {
                try {
                    DB::beginTransaction();
                    if ($request->observacion_aut) {
                        $transaccion->autorizaciones()->attach($datos['autorizacion'], ['observacion' => $datos['observacion_aut']]);
                    } else {
                        $transaccion->autorizaciones()->attach($datos['autorizacion_id']);
                    }
                    $transaccion->detalles()->detach();
                    foreach ($request->listadoProductosSeleccionados as $listado) { //Guarda los productos seleccionados
                        $transaccion->detalles()->attach($listado['id'], ['cantidad_inicial' => $listado['cantidades']]);
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar la autorización'.$e->getMessage()], 422);
                }

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje =  'Autorización actualizada correctamente';
                return response()->json(compact('mensaje', 'modelo'));
            }else{
                if (auth()->user()->hasRole(User::ROL_BODEGA)) {
                    try {
                        DB::beginTransaction();
                        if ($request->observacion_est) {
                            $transaccion->estados()->attach($datos['estado'], ['observacion' => $datos['observacion_est']]);
                        } else {
                            $transaccion->estados()->attach($datos['estado_id']);
                        }
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro'], 422);
                    }
                }

                $modelo = new TransaccionBodegaResource($transaccion->refresh());
                $mensaje = 'Estado actualizado correctamente';
                return response()->json(compact('mensaje', 'modelo'));
            }
        }

        /* $message = 'No tienes autorización para modificar esta solicitud';
        $errors = ['message' => $message];
        return response()->json(['errors' => $errors], 422); */
    }

    /**
     * Eliminar
     */
    public function destroy(TransaccionBodega $transaccion)
    {
        $transaccion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
