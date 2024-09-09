<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\AntecedenteFamiliarRequest;
use App\Http\Resources\Medico\AntecedenteFamiliarResource;
use App\Models\Medico\AntecedenteFamiliar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AntecedenteFamiliarController extends Controller
{
    private $entidad = 'Antecedentes Familiares';

    public function __construct()
    {
        $this->middleware('can:puede.ver.antecedentes_familiares')->only('index', 'show');
        $this->middleware('can:puede.crear.antecedentes_familiares')->only('store');
        $this->middleware('can:puede.editar.antecedentes_familiares')->only('update');
        $this->middleware('can:puede.eliminar.antecedentes_familiares')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = AntecedenteFamiliar::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(AntecedenteFamiliarRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $antecedente_familiar = AntecedenteFamiliar::create($datos);
            $modelo = new AntecedenteFamiliarResource($antecedente_familiar);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente  familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(AntecedenteFamiliarRequest $request, AntecedenteFamiliar $antecedente_familiar)
    {
        $modelo = new AntecedenteFamiliarResource($antecedente_familiar);
        return response()->json(compact('modelo'));
    }


    public function update(AntecedenteFamiliarRequest $request, AntecedenteFamiliar $antecedente_familiar)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $antecedente_familiar->update($datos);
            $modelo = new AntecedenteFamiliarResource($antecedente_familiar->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente  familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(AntecedenteFamiliarRequest $request, AntecedenteFamiliar $antecedente_familiar)
    {
        try {
            DB::beginTransaction();
            $antecedente_familiar->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente  familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
