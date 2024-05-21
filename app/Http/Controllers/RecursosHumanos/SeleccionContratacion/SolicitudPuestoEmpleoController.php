<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleoResource;
use App\Models\RecursosHumanos\SeleccionContratacion\SolicitudPuestoEmpleo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $this->middleware('can:puede.eliminar.rrhh_solicitudes_puestos_empleos')->only('update');
    }

    public function index()
    {
        $results = [];
        $results = SolicitudPuestoEmpleo::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(SolicitudPuestoEmpleoRequest $request)
    {
        try {
            DB::beginTransaction();
            $SolicitudPuestoEmpleo = SolicitudPuestoEmpleo::create($request->validated());
            $modelo = new SolicitudPuestoEmpleoResource($SolicitudPuestoEmpleo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al aprobar el gasto' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(Request $request, SolicitudPuestoEmpleo $SolicitudPuestoEmpleo)
    {
        $modelo = $SolicitudPuestoEmpleo;
        return response()->json(compact('modelo'));
    }


    public function update(SolicitudPuestoEmpleoRequest $request, SolicitudPuestoEmpleo $SolicitudPuestoEmpleo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $SolicitudPuestoEmpleo->update($datos);
            $modelo = new SolicitudPuestoEmpleoResource($SolicitudPuestoEmpleo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy( SolicitudPuestoEmpleo $SolicitudPuestoEmpleo)
    {
        $SolicitudPuestoEmpleo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
