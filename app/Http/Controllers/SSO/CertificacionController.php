<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\CertificacionRequest;
use App\Http\Resources\SSO\CertificacionResource;
use App\Models\SSO\Certificacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;
use Throwable;

class CertificacionController extends Controller
{
    private string $entidad = 'Certificación';

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Certificacion::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = CertificacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CertificacionRequest $request
     * @return Response
     * @throws Throwable
     */
    public function store(CertificacionRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $modelo = Certificacion::create($datos);
            $modelo = new CertificacionResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param Certificacion $certificacion
     * @return JsonResponse
     */
    public function show(Certificacion $certificacion)
    {
        $modelo = new CertificacionResource($certificacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CertificacionRequest $request
     * @param Certificacion $certificacion
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(CertificacionRequest $request, Certificacion $certificacion)
    {
        return DB::transaction(function () use ($request, $certificacion) {
            $keys = $request->keys();
            unset($keys['id']);
            $certificacion->update($request->only($request->keys()));

            // Respuesta
            $modelo = new CertificacionResource($certificacion->refresh());
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
