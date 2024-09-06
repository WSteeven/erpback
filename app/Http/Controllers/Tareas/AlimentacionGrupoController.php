<?php

namespace App\Http\Controllers\Tareas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tareas\AlimentacionGrupoRequest;
use App\Http\Resources\Tareas\AlimentacionGrupoResource;
use App\Models\Tareas\AlimentacionGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\App\Sistema\PaginationService;
use Src\Shared\Utils;

class AlimentacionGrupoController extends Controller
{
    private $entidad = 'Alimentación de grupos';
    protected PaginationService $paginationService;

    public function __construct()
    {
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

        if ($search) $query = AlimentacionGrupo::search($search)->query(fn($q) => $q->latest());
        else $query = AlimentacionGrupo::ignoreRequest(['campos', 'page', 'paginate', 'like'])->filter()->latest();

        if ($paginate) $paginated = $this->paginationService->paginate($query, 100, request('page'));
        else $paginated = $query->get();

        return AlimentacionGrupoResource::collection($paginated);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlimentacionGrupoRequest $request)
    {
        $data = $request->validated();
        $listado = $data['alimentacion_grupos'];

        foreach ($listado as $alimentacionGrupo) {
            // $alimentacionGrupo['tipo_alimentacion_id'] = $tipo_alimentacion_id;
            AlimentacionGrupo::create($alimentacionGrupo);
            // Varios tipos de alimentacion
            /* foreach ($alimentacionGrupo['tipos_alimentacion'] as $tipo_alimentacion_id) {
                $alimentacionGrupo['tipo_alimentacion_id'] = $tipo_alimentacion_id;
                AlimentacionGrupo::create($alimentacionGrupo);
            } */
        }
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
        return response()->json(compact('mensaje'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AlimentacionGrupo $alimentacion_grupo)
    {
        $modelo = new AlimentacionGrupoResource($alimentacion_grupo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlimentacionGrupoRequest $request, AlimentacionGrupo $alimentacion_grupo)
    {
        DB::beginTransaction();

        try {
            $actualizado = $alimentacion_grupo->update($request->except(['id']));

            // Respuesta
            $modelo = new AlimentacionGrupoResource($alimentacion_grupo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            return response()->json(compact('mensaje', 'modelo'));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json(compact('modelo', 'mensaje'));
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
