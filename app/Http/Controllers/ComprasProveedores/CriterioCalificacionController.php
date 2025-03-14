<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComprasProveedores\CriterioCalificacionRequest;
use App\Http\Resources\ComprasProveedores\CriterioCalificacionResource;
use App\Models\ComprasProveedores\CriterioCalificacion;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class CriterioCalificacionController extends Controller
{
    private string $entidad = 'Criterio';
    public function __construct()
    {
        $this->middleware('can:puede.ver.criterios_calificaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.criterios_calificaciones')->only('store');
        $this->middleware('can:puede.editar.criterios_calificaciones')->only('update');
        $this->middleware('can:puede.eliminar.criterios_calificaciones')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        if (auth()->user()->hasRole([User::ROL_COMPRAS, User::ROL_ADMINISTRADOR])) {
            if ($request->boolean('only_me')) { //variable auxiliar para listar solo los criterios que pertenecen a mi departamento
                $results = CriterioCalificacion::where('departamento_id', auth()->user()->empleado->departamento_id)->ignoreRequest(['only_me'])->filter()->get();
            } else {
                // $results = CriterioCalificacion::all();
                $results = CriterioCalificacion::where('departamento_id', auth()->user()->empleado->departamento_id)->filter()->get();
            }
        } else {
            $results = CriterioCalificacion::where('departamento_id', auth()->user()->empleado->departamento_id)->ignoreRequest(['only_me'])->filter()->get();
        }
        $results = CriterioCalificacionResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CriterioCalificacionRequest $request
     * @return JsonResponse
     */
    public function store(CriterioCalificacionRequest $request)
    {
        //Adaptacion de foreing keys
        $datos = $request->validated();
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];
        $datos['oferta_id'] = $request->safe()->only(['oferta'])['oferta'];

        $modelo = CriterioCalificacion::create($datos);
        $modelo = new CriterioCalificacionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param CriterioCalificacion $criterio
     * @return JsonResponse
     */
    public function show(CriterioCalificacion $criterio)
    {
        $modelo = new CriterioCalificacionResource($criterio);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CriterioCalificacionRequest $request
     * @param CriterioCalificacion $criterio
     * @return JsonResponse
     */
    public function update(CriterioCalificacionRequest $request, CriterioCalificacion $criterio)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida en update:', $request->all()]);
        //Adaptacion de foreing keys
        $datos = $request->validated();
        $datos['departamento_id'] = $request->safe()->only(['departamento'])['departamento'];
        $datos['oferta_id'] = $request->safe()->only(['oferta'])['oferta'];

        //Respuesta
        $criterio->update($datos);
        $modelo = new CriterioCalificacionResource($criterio->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CriterioCalificacion $criterio
     * @return JsonResponse
     */
    public function destroy(CriterioCalificacion $criterio)
    {
        $criterio->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
