<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Http\Requests\SSO\SeguimientoIncidenteRequest;
use App\Http\Resources\SSO\AccidenteResource;
use App\Http\Resources\SSO\SeguimientoIncidenteResource;
use App\Models\SSO\Accidente;
use App\Models\SSO\SeguimientoIncidente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Config\Permisos;
use Src\Shared\Utils;

class SeguimientoIncidenteController extends Controller
{
    private string $entidad = 'Accidente';

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'seguimiento_incidentes')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'seguimiento_incidentes')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'seguimiento_incidentes')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = SeguimientoIncidente::ignoreRequest(['campos'])->filter()->get();
        $results = SeguimientoIncidenteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(SeguimientoIncidenteRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param SeguimientoIncidente $seguimiento_incidente
     * @return JsonResponse
     */
    public function show(SeguimientoIncidente $seguimiento_incidente)
    {
        $modelo = new SeguimientoIncidenteResource($seguimiento_incidente);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(SeguimientoIncidenteRequest $request, SeguimientoIncidente $seguimiento_incidente)
    {
        return DB::transaction(function () use ($request, $seguimiento_incidente) {
            $datos = $request->validated();
            $seguimiento_incidente->update([
                'causa_raiz' => $datos['causa_raiz'],
                'acciones_correctivas' => $datos['acciones_correctivas'],
            ]);
            $modelo = new SeguimientoIncidenteResource($seguimiento_incidente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
