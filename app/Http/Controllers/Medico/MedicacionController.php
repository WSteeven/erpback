<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicacionRequest;
use App\Http\Resources\Medico\MedicacionResource;
use App\Models\Medico\Medicacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class MedicacionController extends Controller
{
    private $entidad = 'Medicacion';

    public function __construct()
    {
        $this->middleware('can:puede.ver.medicaciones')->only('index', 'show');
        $this->middleware('can:puede.crear.medicaciones')->only('store');
        $this->middleware('can:puede.editar.medicaciones')->only('update');
        $this->middleware('can:puede.eliminar.medicaciones')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = Medicacion::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(MedicacionRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $medicacion = Medicacion::create($datos);
            $modelo = new MedicacionResource($medicacion);
            $this->tabla_roles($medicacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(MedicacionRequest $request, Medicacion $medicacion)
    {
        $modelo = new MedicacionResource($medicacion);
        return response()->json(compact('modelo'));
    }


    public function update(MedicacionRequest $request, Medicacion $medicacion)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $medicacion->update($datos);
            $modelo = new MedicacionResource($medicacion->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(MedicacionRequest $request, Medicacion $medicacion)
    {
        try {
            DB::beginTransaction();
            $medicacion->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacion' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
