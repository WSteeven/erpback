<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoAptitudMedicaLaboralRequest;
use App\Http\Resources\Medico\TipoAptitudMedicaLaboralResource;
use App\Models\Medico\TipoAptitudMedicaLaboral;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoAptitudMedicaLaboralController extends Controller
{
    private $entidad = 'Tipo de aptitud medica laboral medica laboral';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_aptitudes_medicas_laborales')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_aptitudes_medicas_laborales')->only('store');
        $this->middleware('can:puede.editar.tipos_aptitudes_medicas_laborales')->only('update');
        $this->middleware('can:puede.eliminar.tipos_aptitudes_medicas_laborales')->only('destroy');
    }

    public function index()
    {
        $results = TipoAptitudMedicaLaboral::ignoreRequest(['campos'])->filter()->get();
        $results = TipoAptitudMedicaLaboralResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(TipoAptitudMedicaLaboralRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_aptitud_medica_laboral = TipoAptitudMedicaLaboral::create($datos);
            $modelo = new TipoAptitudMedicaLaboralResource($tipo_aptitud_medica_laboral);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud medica laboral' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoAptitudMedicaLaboral $tipo_aptitud_medica_laboral)
    {
        $modelo = new TipoAptitudMedicaLaboralResource($tipo_aptitud_medica_laboral);
        return response()->json(compact('modelo'));
    }


    public function update(TipoAptitudMedicaLaboralRequest $request, TipoAptitudMedicaLaboral $tipo_aptitud_medica_laboral)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_aptitud_medica_laboral->update($datos);
            $modelo = new TipoAptitudMedicaLaboralResource($tipo_aptitud_medica_laboral->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud medica laboral' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoAptitudMedicaLaboral $tipo_aptitud_medica_laboral)
    {
        try {
            DB::beginTransaction();
            $tipo_aptitud_medica_laboral->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud medica laboral' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
