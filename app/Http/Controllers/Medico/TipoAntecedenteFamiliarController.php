<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoHabitoToxicoRequest;
use App\Http\Resources\Medico\TipoHabitoToxicoResource;
use App\Models\Medico\TipoAntecedenteFamiliar;
use App\Models\Medico\TipoHabitoToxico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoAntecedenteFamiliarController extends Controller
{
    private $entidad = 'Tipo de antecedente familiar';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_antecedentes_familiares')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_antecedentes_familiares')->only('store');
        $this->middleware('can:puede.editar.tipos_antecedentes_familiares')->only('update');
        $this->middleware('can:puede.eliminar.tipos_antecedentes_familiares')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoAntecedenteFamiliar::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoHabitoToxicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_antecedente_familiar = TipoHabitoToxico::create($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_antecedente_familiar);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_antecedente_familiar)
    {
        $modelo = new TipoHabitoToxicoResource($tipo_antecedente_familiar);
        return response()->json(compact('modelo'));
    }


    public function update(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_antecedente_familiar)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_antecedente_familiar->update($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_antecedente_familiar->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_antecedente_familiar)
    {
        try {
            DB::beginTransaction();
            $tipo_antecedente_familiar->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de antecedente familiar' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
