<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\TipoEventoBitacoraRequest;
use App\Http\Resources\Seguridad\TipoEventoBitacoraResource;
use App\Models\Seguridad\TipoEventoBitacora;
use DB;
use Illuminate\Http\Request;
use Src\Config\Permisos;
use Src\Shared\Utils;

class TipoEventoBitacoraController extends Controller
{
    private string $entidad = 'Tipo de evento de bitacora';

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'tipos_eventos_bitacoras')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'tipos_eventos_bitacoras')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'tipos_eventos_bitacoras')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = TipoEventoBitacora::ignoreRequest(['paginate'])->filter()->latest()->get();
        $results = TipoEventoBitacoraResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoEventoBitacoraRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $modelo = TipoEventoBitacora::create($datos);

            $modelo = new TipoEventoBitacoraResource($modelo->refresh());
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
    public function show(TipoEventoBitacora $tipo_evento_bitacora)
    {
        $modelo = new TipoEventoBitacoraResource($tipo_evento_bitacora);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoEventoBitacoraRequest $request, TipoEventoBitacora $tipo_evento_bitacora)
    {
        return DB::transaction(function () use ($request, $tipo_evento_bitacora) {
            $datos = $request->validated();
            $tipo_evento_bitacora->update($datos);
            $modelo = new TipoEventoBitacoraResource($tipo_evento_bitacora->refresh());
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
