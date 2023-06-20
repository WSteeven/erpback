<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolicitudPrestamoEmpresarialRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarialResource;
use App\Models\RecursosHumanos\NominaPrestamos\SolicitudPrestamoEmpresarial;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class SolicitudPrestamoEmpresarialController extends Controller
{
    private $entidad = 'Solicitud Prestamo Empresarial';
    public function __construct()
    {
        $this->middleware('can:puede.ver.solicitud_prestamo_empresarial')->only('index', 'show');
        $this->middleware('can:puede.crear.solicitud_prestamo_empresarial')->only('store');
        $this->middleware('can:puede.editar.solicitud_prestamo_empresarial')->only('update');
        $this->middleware('can:puede.eliminar.solicitud_prestamo_empresarial')->only('update');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = SolicitudPrestamoEmpresarial::ignoreRequest(['campos'])->filter()->get();
        $results = SolicitudPrestamoEmpresarialResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show(Request $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        return response()->json(compact('modelo'), 200);
    }
    public function store(SolicitudPrestamoEmpresarialRequest $request)
    {
        $datos = $request->validated();
        $SolicitudPrestamoEmpresarial = SolicitudPrestamoEmpresarial::create($datos);
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    public function update(SolicitudPrestamoEmpresarialRequest $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $datos = $request->validated();
        $SolicitudPrestamoEmpresarial->update($datos);
        $modelo = new SolicitudPrestamoEmpresarialResource($SolicitudPrestamoEmpresarial);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $SolicitudPrestamoEmpresarial;
    }
    public function destroy(Request $request, SolicitudPrestamoEmpresarial $SolicitudPrestamoEmpresarial)
    {
        $SolicitudPrestamoEmpresarial->delete();
        return response()->json(compact('SolicitudPrestamoEmpresarial'));
    }
}
