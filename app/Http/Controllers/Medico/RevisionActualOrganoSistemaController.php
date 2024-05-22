<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RevisionActualOrganoSistemaRequest;
use App\Http\Resources\Medico\RevisionActualOrganoSistemaResource;
use App\Models\Medico\RevisionActualOrganoSistema;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RevisionActualOrganoSistemaController extends Controller
{
    private $entidad = 'Factor de riesgo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.revisiones_actuales_organos_sistemas')->only('index', 'show');
        $this->middleware('can:puede.crear.revisiones_actuales_organos_sistemas')->only('store');
        $this->middleware('can:puede.editar.revisiones_actuales_organos_sistemas')->only('update');
        $this->middleware('can:puede.eliminar.revisiones_actuales_organos_sistemas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = RevisionActualOrganoSistema::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(RevisionActualOrganoSistemaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $revision_actual_organo_sistema = RevisionActualOrganoSistema::create($datos);
            $modelo = new RevisionActualOrganoSistemaResource($revision_actual_organo_sistema);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de revision actual del sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(RevisionActualOrganoSistemaRequest $request, RevisionActualOrganoSistema $revision_actual_organo_sistema)
    {
        $modelo = new RevisionActualOrganoSistemaResource($revision_actual_organo_sistema);
        return response()->json(compact('modelo'));
    }


    public function update(RevisionActualOrganoSistemaRequest $request, RevisionActualOrganoSistema $revision_actual_organo_sistema)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $revision_actual_organo_sistema->update($datos);
            $modelo = new RevisionActualOrganoSistemaResource($revision_actual_organo_sistema->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de revision actual del sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(RevisionActualOrganoSistemaRequest $request, RevisionActualOrganoSistema $revision_actual_organo_sistema)
    {
        try {
            DB::beginTransaction();
            $revision_actual_organo_sistema->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de revision actual del sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
