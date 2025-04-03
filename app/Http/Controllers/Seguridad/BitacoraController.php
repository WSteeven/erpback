<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\BitacoraRequest;
use App\Http\Resources\Seguridad\BitacoraResource;
use App\Models\Seguridad\Bitacora;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Src\App\Sistema\PaginationService;
use Src\Config\Permisos;
use Src\Shared\Utils;

class BitacoraController extends Controller
{
    private string $entidad = 'Bitácora';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'bitacoras')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'bitacoras')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'bitacoras')->only('update');

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

        $query = Bitacora::query();

        if (Auth::user()->hasRole(User::ROL_GUARDIA)) {
            if ($search) $query = Bitacora::search($search)->where('agente_turno_id', Auth::user()->empleado->id);
            else $query = Bitacora::ignoreRequest(['paginate'])->filter()->where('agente_turno_id', Auth::user()->empleado->id)->latest();
        } else {
            if ($search) $query = Bitacora::search($search);
            else $query = Bitacora::ignoreRequest(['paginate'])->filter()->latest();
        }

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();

        return BitacoraResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BitacoraRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $modelo = Bitacora::create($datos);

            $modelo = new BitacoraResource($modelo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
            return response()->json(compact('mensaje', 'modelo'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bitacora $bitacora)
    {
        $modelo = new BitacoraResource($bitacora);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BitacoraRequest $request, Bitacora $bitacora)
    {
        return DB::transaction(function () use ($request, $bitacora) {
            $datos = $request->validated();
            $bitacora->update($datos);
            $modelo = new BitacoraResource($bitacora->refresh());
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
