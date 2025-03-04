<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\ZonaRequest;
use App\Http\Resources\Seguridad\ZonaResource;
use App\Models\Seguridad\Zona;
use DB;
use Illuminate\Http\Request;
use Src\App\Sistema\PaginationService;
use Src\Config\Permisos;
use Src\Shared\Utils;

class ZonaController extends Controller
{
    private string $entidad = 'Zona';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'zonas')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'zonas')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'zonas')->only('update');

        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        $paginate = request('paginate');

        if ($search) $query = Zona::search($search);
        else $query = Zona::ignoreRequest(['paginate'])->filter()->latest();

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();

        return ZonaResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ZonaRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $modelo = Zona::create($datos);
            $modelo->empleados()->sync($datos['empleados_asignados_ids']); 

            $modelo = new ZonaResource($modelo->refresh());
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
    public function show(Zona $zona)
    {
        $modelo = new ZonaResource($zona);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ZonaRequest $request, Zona $zona)
    {
        return DB::transaction(function () use ($request, $zona) {
            $datos = $request->validated();
            $zona->update($datos);
            $zona->empleados()->sync($datos['empleados_asignados_ids']); 
            $modelo = new ZonaResource($zona->refresh());
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
