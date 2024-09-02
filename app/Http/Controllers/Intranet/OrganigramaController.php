<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\OrganigramaRequest;
use App\Http\Resources\Intranet\OrganigramaResource;
use App\Models\Intranet\Organigrama;
use DB;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;
use Src\Shared\Utils;
use Throwable;

class OrganigramaController extends Controller
{
    private string $entidad = 'Organigrama';

    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_organigrama')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_organigrama')->only('store');
        $this->middleware('can:puede.editar.intra_organigrama')->only('update');
        $this->middleware('can:puede.eliminar.intra_organigrama')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Organigrama::ignoreRequest(['estado'])->filter()->orderBy('empleado_id', 'asc')->get();
        $results = OrganigramaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrganigramaRequest $request
     * @return JsonResponse
     */
    public function store(OrganigramaRequest $request)
    {
        try {
            DB::beginTransaction();
            // Respuesta
            $datos = $request->validated();
            $modelo = Organigrama::create($datos);
            $modelo = new OrganigramaResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Organigrama $organigrama
     * @return JsonResponse
     */
    public function show(Organigrama $organigrama)
    {
        $modelo = new OrganigramaResource($organigrama);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrganigramaRequest $request
     * @param Organigrama $organigrama
     * @return JsonResponse
     */
    public function update(OrganigramaRequest $request, Organigrama $organigrama)
    {
        try {
            DB::beginTransaction();
            // Respuesta
            $datos = $request->validated();
            $organigrama->update($datos);
            $modelo = new OrganigramaResource($organigrama->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organigrama $organigrama
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Organigrama $organigrama)
    {
//        $organigrama->delete();
//        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        try {
            throw new Exception('Método no definido, comunicate con el departamento informático para más información');
        } catch (Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
//        return response()->json(compact('mensaje'));
    }
}
