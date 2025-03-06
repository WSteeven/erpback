<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\PrendaZonaRequest;
use App\Http\Resources\Seguridad\PrendaZonaResource;
use App\Models\DetalleProducto;
use App\Models\Seguridad\MiembroZona;
use App\Models\Seguridad\PrendaZona;
use App\Models\Seguridad\RestriccionPrendaZona;
use DB;
use Illuminate\Http\Request;
use Log;
use Src\App\Sistema\PaginationService;
use Src\Config\Permisos;
use Src\Shared\Utils;

class PrendaZonaController extends Controller
{
    private string $entidad = 'Prenda zona';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->middleware('can:' . Permisos::VER . 'prendas_zonas')->only('index', 'show');
        $this->middleware('can:' . Permisos::CREAR . 'prendas_zonas')->only('store');
        $this->middleware('can:' . Permisos::EDITAR . 'prendas_zonas')->only('update');

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

        if ($search) $query = PrendaZona::search($search);
        else $query = PrendaZona::ignoreRequest(['paginate'])->filter()->latest();

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();

        return PrendaZonaResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrendaZonaRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();

            $datos['detalles_productos'] = json_encode($datos['detalles_productos']);
            $modelo = PrendaZona::create($datos);

            $modelo = new PrendaZonaResource($modelo->refresh());
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
    public function show(PrendaZona $prenda_zona)
    {
        $modelo = new PrendaZonaResource($prenda_zona);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PrendaZonaRequest $request, PrendaZona $prenda_zona)
    {
        return DB::transaction(function () use ($request, $prenda_zona) {
            $datos = $request->validated();
            $datos['detalles_productos'] = json_encode($datos['detalles_productos']);
            $prenda_zona->update($datos);
            $modelo = new PrendaZonaResource($prenda_zona->refresh());
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

    public function existeZona(Request $request)
    {
        $request->validate([
            'zona_id' => ['required', 'integer', 'exists:seg_zonas,id']
        ]);

        if (PrendaZona::where('zona_id', $request->zona_id)->exists()) {
            return response()->noContent(); // Código 204 sin contenido
        } else return response()->noContent(404);
    }

    public function consultarPrendasZona(Request $request)
    {
        $request->validate([
            'zona_id' => ['required', 'integer', 'exists:seg_zonas,id'],
            'empleado_id' => ['required', 'integer', 'exists:empleados,id'],
        ]);

        $results = [];

        $prendaZona = PrendaZona::where('zona_id', $request['zona_id'])->first();

        if (is_null($prendaZona)) return response()->json(compact('results'));

        if (!$prendaZona->tiene_restricciones) {
            $results = json_decode($prendaZona->detalles_productos);
        } else {
            $miembro_zona_id = MiembroZona::where('zona_id', $request['zona_id'])->where('empleado_id', $request['empleado_id'])->first()->id;
            $detalles_productos_ids = RestriccionPrendaZona::where('miembro_zona_id', $miembro_zona_id)->pluck('detalle_producto_id');
            $results = count($detalles_productos_ids) > 0 ? DetalleProducto::whereIn('id', $detalles_productos_ids)->get() : json_decode($prendaZona->detalles_productos);
        }

        return response()->json(compact('results'));
    }
}
