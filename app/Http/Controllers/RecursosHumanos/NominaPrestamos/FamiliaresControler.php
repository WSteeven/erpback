<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\FamiliaresRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\FamiliaresResource;
use App\Models\RecursosHumanos\NominaPrestamos\Familiares;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class FamiliaresControler extends Controller
{
    private $entidad = 'PERMISO_EMPLEADO';
    public function __construct()
    {
        $this->middleware('can:puede.ver.familiares')->only('index', 'show');
        $this->middleware('can:puede.crear.familiares')->only('store');
    }

    public function index(Request $request)
    {
        $results = [];
        $results = Familiares::ignoreRequest(['campos'])->filter()->get();
        $results = FamiliaresResource::collection($results);
        return response()->json(compact('results'));
    }

    public function create(Request $request)
    {
        $permisoEmpleado = new Familiares();
        $permisoEmpleado->nombre = $request->nombre;
        $permisoEmpleado->save();
        return $permisoEmpleado;
    }

    public function store(FamiliaresRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();
            $permisoEmpleado = Familiares::create($datos);
            $modelo = new FamiliaresResource($permisoEmpleado);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['ERROR en el insert de permiso de empleado', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(Familiares $FamiliaresEmpleado,$idFamiliar)
    {
        $FamiliaresEmpleado = Familiares::where('id',$idFamiliar)->first();
        $modelo = new FamiliaresResource($FamiliaresEmpleado);
        return response()->json(compact('modelo'), 200);
    }

    public function update(FamiliaresRequest $request, $FamiliaresEmpleadoId)
    {
        $datos = $request->validated();
        $permisoEmpleado = Familiares::find($FamiliaresEmpleadoId);
        $permisoEmpleado->update($datos);
        $modelo = new FamiliaresResource($permisoEmpleado);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function destroy($FamiliaresEmpleadoId)
    {
        $permisoEmpleado = Familiares::find($FamiliaresEmpleadoId);
        $permisoEmpleado->delete();
        return $permisoEmpleado;
    }}
