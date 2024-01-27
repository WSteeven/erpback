<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\SistemaOrganicoRequest;
use App\Http\Resources\Medico\SistemaOrganicoResource;
use App\Models\Medico\SistemaOrganico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class SistemaOrganicoController extends Controller
{
    private $entidad = 'Sistema Organico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.sistemas_organicos')->only('index', 'show');
        $this->middleware('can:puede.crear.sistemas_organicos')->only('store');
        $this->middleware('can:puede.editar.sistemas_organicos')->only('update');
        $this->middleware('can:puede.eliminar.sistemas_organicos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = SistemaOrganico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(SistemaOrganicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $sistema_organico = SistemaOrganico::create($datos);
            $modelo = new SistemaOrganicoResource($sistema_organico);
            $this->tabla_roles($sistema_organico);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(SistemaOrganicoRequest $request, SistemaOrganico $sistema_organico)
    {
        $modelo = new SistemaOrganicoResource($sistema_organico);
        return response()->json(compact('modelo'));
    }


    public function update(SistemaOrganicoRequest $request, SistemaOrganico $sistema_organico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $sistema_organico->update($datos);
            $modelo = new SistemaOrganicoResource($sistema_organico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(SistemaOrganicoRequest $request, SistemaOrganico $sistema_organico)
    {
        try {
            DB::beginTransaction();
            $sistema_organico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de sistema organico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
