<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\CertificacionEmpleadoRequest;
use App\Http\Resources\SSO\CertificacionEmpleadoResource;
use App\Http\Resources\SSO\CertificacionResource;
use App\Models\SSO\CertificacionEmpleado;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;
use Throwable;

class CertificacionEmpleadoController extends Controller
{
    private string $entidad = 'Certificación de empleado';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = CertificacionEmpleado::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = CertificacionEmpleadoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CertificacionEmpleadoRequest $request
     * @return Response
     * @throws Throwable
     */
    public function store(CertificacionEmpleadoRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $datos['certificaciones_id'] = json_encode($datos['certificaciones_id']);
            $modelo = CertificacionEmpleado::create($datos);
            $modelo = new CertificacionEmpleadoResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param CertificacionEmpleado $certificacion_empleado
     * @return JsonResponse
     */
    public function show(CertificacionEmpleado $certificacion_empleado)
    {
        $modelo = new CertificacionEmpleadoResource($certificacion_empleado);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CertificacionEmpleadoRequest $request
     * @param CertificacionEmpleado $certificacion_empleado
     * @return Response
     * @throws Throwable
     */
    public function update(CertificacionEmpleadoRequest $request, CertificacionEmpleado $certificacion_empleado)
    {
        return DB::transaction(function () use ($request, $certificacion_empleado) {
            $keys = $request->keys();
            unset($keys['id']);
            $request['certificaciones_id'] = json_encode($request['certificaciones_id']);
            $certificacion_empleado->update($request->only($request->keys()));

            // Respuesta
            $modelo = new CertificacionEmpleadoResource($certificacion_empleado->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
