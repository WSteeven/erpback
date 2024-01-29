<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\AptitudMedicaRequest;
use App\Http\Resources\Medico\AptitudMedicaResource;
use App\Models\Medico\AptitudMedica;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class AptitudMedicaController extends Controller
{
    private $entidad = 'Actividad de puesto de trabajo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.aptitudes_medicas')->only('index', 'show');
        $this->middleware('can:puede.crear.aptitudes_medicas')->only('store');
        $this->middleware('can:puede.editar.aptitudes_medicas')->only('update');
        $this->middleware('can:puede.eliminar.aptitudes_medicas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = AptitudMedica::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(AptitudMedicaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $aptitud_medica = AptitudMedica::create($datos);
            $modelo = new AptitudMedicaResource($aptitud_medica);
            $this->tabla_roles($aptitud_medica);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(AptitudMedicaRequest $request, AptitudMedica $aptitud_medica)
    {
        $modelo = new AptitudMedicaResource($aptitud_medica);
        return response()->json(compact('modelo'));
    }


    public function update(AptitudMedicaRequest $request, AptitudMedica $aptitud_medica)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $aptitud_medica->update($datos);
            $modelo = new AptitudMedicaResource($aptitud_medica->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(AptitudMedicaRequest $request, AptitudMedica $aptitud_medica)
    {
        try {
            DB::beginTransaction();
            $aptitud_medica->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de actividad de puesto de trabajo' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

}
