<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\VisitanteRequest;
use App\Http\Resources\Seguridad\VisitanteResource;
use App\Models\Seguridad\Visitante;
use DB;
use Illuminate\Http\Request;
use Src\App\Sistema\PaginationService;
use Src\Config\Permisos;
use Src\Shared\Utils;

class VisitanteController extends Controller
{
    private string $entidad = 'Visitante';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'visitantes')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'visitantes')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'visitantes')->only('update');

        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Visitante::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = VisitanteResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisitanteRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $modelo = Visitante::create($datos);

            $modelo = new VisitanteResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Visitante $visitante)
    {
        $modelo = new VisitanteResource($visitante);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VisitanteRequest $request, Visitante $visitante)
    {
        return DB::transaction(function () use ($request, $visitante) {
            $datos = $request->validated();
            $visitante->update($datos);
            $modelo = new VisitanteResource($visitante->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
