<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoVacunaRequest;
use App\Http\Resources\Medico\TipoVacunaResource;
use App\Models\Medico\TipoVacuna;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class TipoVacunaController extends Controller
{
    private $entidad = 'Tipo de examen';

    public function __construct()
    {
        $this->middleware('can:puede.ver.med_tipos_vacunas')->only('index', 'show');
        $this->middleware('can:puede.crear.med_tipos_vacunas')->only('store');
        $this->middleware('can:puede.editar.med_tipos_vacunas')->only('update');
        $this->middleware('can:puede.eliminar.med_tipos_vacunas')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoVacuna::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoVacunaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_examen = TipoVacuna::create($datos);
            $modelo = new TipoVacunaResource($tipo_examen);
            $this->tabla_roles($tipo_examen);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de rol de pago' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoVacunaRequest $request, TipoVacuna $tipo_examen)
    {
        $modelo = new TipoVacunaResource($tipo_examen);
        return response()->json(compact('modelo'));
    }


    public function update(TipoVacunaRequest $request, TipoVacuna $tipo_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_examen->update($datos);
            $modelo = new TipoVacunaResource($tipo_examen->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de rol de pago' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoVacunaRequest $request, TipoVacuna $tipo_examen)
    {
        try {
            DB::beginTransaction();
            $tipo_examen->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de rol de pago' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
}
}
