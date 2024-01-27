<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EsquemaVacunaRequest;
use App\Http\Resources\Medico\EsquemaVacunaResource;
use App\Models\Medico\EsquemaVacuna;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EsquemaVacunaController extends Controller
{
    private $entidad = 'Esquema de vacunacion';

    public function __construct()
    {
        $this->middleware('can:puede.ver.med_esquemas_vacunas')->only('index', 'show');
        $this->middleware('can:puede.crear.med_esquemas_vacunas')->only('store');
        $this->middleware('can:puede.editar.med_esquemas_vacunas')->only('update');
        $this->middleware('can:puede.eliminar.med_esquemas_vacunas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = EsquemaVacuna::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(EsquemaVacunaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $esquema_vacuna = EsquemaVacuna::create($datos);
            $modelo = new EsquemaVacunaResource($esquema_vacuna);
            $this->tabla_roles($esquema_vacuna);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        $modelo = new EsquemaVacunaResource($esquema_vacuna);
        return response()->json(compact('modelo'));
    }


    public function update(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $esquema_vacuna->update($datos);
            $modelo = new EsquemaVacunaResource($esquema_vacuna->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al actualizar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EsquemaVacunaRequest $request, EsquemaVacuna $esquema_vacuna)
    {
        try {
            DB::beginTransaction();
            $esquema_vacuna->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al eliminar el registro de categoria de examen' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

}
