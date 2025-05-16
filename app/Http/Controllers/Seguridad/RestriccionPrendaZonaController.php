<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seguridad\RestriccionPrendaZonaRequest;
use App\Http\Resources\Seguridad\RestriccionPrendaZonaResource;
use App\Models\Seguridad\RestriccionPrendaZona;
use DB;
use Illuminate\Http\Request;
use Log;

class RestriccionPrendaZonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = RestriccionPrendaZona::ignoreRequest([''])->filter()->latest()->get();
        $results = RestriccionPrendaZonaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestriccionPrendaZonaRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->validated();
            // Log::channel('testing')->info('Log', ['Datos: ', $datos]);
            if (count($datos['listado'])) {
                RestriccionPrendaZona::insert($datos['listado']);
                $results = RestriccionPrendaZona::where('miembro_zona_id', $datos['listado'][0]['miembro_zona_id'])->get();
                $results = RestriccionPrendaZonaResource::collection($results);
            }

            $mensaje = 'Restricción actualizada exitosamente!';
            return response()->json(compact('results', 'mensaje'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        /* $ids = request('ids');
        $miembro_zona_id = RestriccionPrendaZona::where('id', $ids[0])->first()->pluck('miembro_zona_id');
        $results = RestriccionPrendaZona::where('miembro_zona_id', $miembro_zona_id)->get();
        RestriccionPrendaZona::destroy($ids);
        $results = RestriccionPrendaZonaResource::collection($results);
        return response()->json(compact('results')); */
    }

    public function destroyMultipleByIds(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $ids = explode(',', $request->input('ids')); // Obtener array de IDs

            if (empty($ids)) {
                return response()->json(['message' => 'No se proporcionaron IDs'], 400);
            }

            $miembro_zona_id = RestriccionPrendaZona::where('id', $ids[0])->first()->miembro_zona_id;
            RestriccionPrendaZona::whereIn('id', $ids)->delete();
            $results = RestriccionPrendaZona::where('miembro_zona_id', $miembro_zona_id)->get();
            $results = RestriccionPrendaZonaResource::collection($results);
            $mensaje = 'Restricción actualizada exitosamente!';
            return response()->json(compact('results', 'mensaje'));
        });
    }

    public function destroyMultipleByData(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $datos = $request->input(); // Obtener el array de objetos

            if (empty($datos)) {
                return response()->json(['message' => 'No se proporcionaron datos'], 400);
            }

            // Construir condiciones para eliminar
            foreach ($datos as $dato) {
                RestriccionPrendaZona::where('detalle_producto_id', $dato['detalle_producto_id'])
                    ->where('miembro_zona_id', $dato['miembro_zona_id'])
                    ->delete();
            }

            $mensaje = 'Restricciones eliminadas y lista actualizada correctamente!';
            return response()->json(compact('mensaje'));
        });
    }
}
