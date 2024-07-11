<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleo;
use Dflydev\DotAccessData\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class SolicitudPuestoEmpleoController extends Controller
{
    private $entidad = 'Solicitud de Puesto';

    public function __construct()
    {
        $this->middleware('can:puede.ver.rrhh_solicitudes_puestos_empleos')->only('index', 'show');
        $this->middleware('can:puede.crear.rrhh_solicitudes_puestos_empleos')->only('store');
        $this->middleware('can:puede.editar.rrhh_solicitudes_puestos_empleos')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_solicitudes_puestos_empleos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = SolicitudPuestoEmpleo::ignoreRequest(['campos'])->filter()->get();
        $results = SolicitudPuestoEmpleoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(SolicitudPuestoEmpleoRequest $request)
    {
        Log::channel('testing')->info('Log', ['request en store', $request->all()]);
        // Log::channel('testing')->info('Log', ['ids', $request]);
        $modelo = [];
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        try {
            DB::beginTransaction();
            $solicitud = SolicitudPuestoEmpleo::create($request->validated());
            $modelo = new SolicitudPuestoEmpleoResource($solicitud);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(SolicitudPuestoEmpleo $solicitud)
    {
        $modelo = new SolicitudPuestoEmpleoResource($solicitud);

        return response()->json(compact('modelo'));
    }


    public function update(SolicitudPuestoEmpleoRequest $request, SolicitudPuestoEmpleo $solicitud)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $solicitud->update($datos);
            $modelo = new SolicitudPuestoEmpleoResource($solicitud->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy(SolicitudPuestoEmpleo $solicitud)
    {
        $solicitud->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
