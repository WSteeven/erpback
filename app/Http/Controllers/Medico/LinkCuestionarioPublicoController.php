<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\LinkCuestionarioPublicoRequest;
use App\Http\Resources\Medico\LinkCuestionarioPublicoResource;
use App\Models\Medico\LinkCuestionarioPublico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class LinkCuestionarioPublicoController extends Controller
{
    private $entidad = 'Link';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = LinkCuestionarioPublico::ignoreRequest(['campos'])->filter()->latest()->get();
        $results = LinkCuestionarioPublicoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LinkCuestionarioPublicoRequest $request)
    {
        try {
            $datos = $request->validated();

            $modelo = LinkCuestionarioPublico::create($datos);
            $modelo = new LinkCuestionarioPublicoResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
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
    public function update(LinkCuestionarioPublicoRequest $request, LinkCuestionarioPublico $link_cuestionario_publico)
    {
        try {
            DB::beginTransaction();

            $keys = $request->keys();
            unset($keys['id']);
            $link_cuestionario_publico->update($request->only($request->keys()));

            $modelo = new LinkCuestionarioPublicoResource($link_cuestionario_publico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
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
