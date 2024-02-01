<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\AntecedenteGinecoObstetricoRequest;
use App\Http\Resources\Medico\AntecedenteGinecoObstetricoResource;
use App\Models\Medico\AntecedenteGinecoObstetrico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AntecedenteGinecoObstetricoController extends Controller
{
    private $entidad = 'Antecedente Gineco Obstetrico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.med_antecedentes_gineco_obstetricos')->only('index', 'show');
        $this->middleware('can:puede.crear.med_antecedentes_gineco_obstetricos')->only('store');
        $this->middleware('can:puede.editar.med_antecedentes_gineco_obstetricos')->only('update');
        $this->middleware('can:puede.eliminar.med_antecedentes_gineco_obstetricos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = AntecedenteGinecoObstetrico::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(AntecedenteGinecoObstetricoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $antecedente_ginecoobstetrico = AntecedenteGinecoObstetrico::create($datos);
            $modelo = new AntecedenteGinecoObstetricoResource($antecedente_ginecoobstetrico);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de antecedente gineco obstetrico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(AntecedenteGinecoObstetricoRequest $request, AntecedenteGinecoObstetrico $antecedente_ginecoobstetrico)
    {
        $modelo = new AntecedenteGinecoObstetricoResource($antecedente_ginecoobstetrico);
        return response()->json(compact('modelo'));
    }


    public function update(AntecedenteGinecoObstetricoRequest $request, AntecedenteGinecoObstetrico $antecedente_ginecoobstetrico)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $antecedente_ginecoobstetrico->update($datos);
            $modelo = new AntecedenteGinecoObstetricoResource($antecedente_ginecoobstetrico->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de antecedente gineco obstetrico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(AntecedenteGinecoObstetricoRequest $request, AntecedenteGinecoObstetrico $antecedente_ginecoobstetrico)
    {
        try {
            DB::beginTransaction();
            $antecedente_ginecoobstetrico->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de antecedente gineco obstetrico' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
