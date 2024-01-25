<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoHabitoToxicoRequest;
use App\Http\Resources\Medico\TipoHabitoToxicoResource;
use App\Models\Medico\TipoHabitoToxico;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;

class TipoHabitoToxicoController extends Controller
{
    private $entidad = 'Tipo de habito toxico';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_habitos_toxicos')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_habitos_toxicos')->only('store');
        $this->middleware('can:puede.editar.tipos_habitos_toxicos')->only('update');
        $this->middleware('can:puede.eliminar.tipos_habitos_toxicos')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoHabitoToxico::ignoreTipoHabitoToxicoRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoHabitoToxicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_examen = TipoHabitoToxico::create($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_examen);
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

    public function show(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_examen)
    {
        $modelo = new TipoHabitoToxicoResource($tipo_examen);
        return response()->json(compact('modelo'));
    }


    public function update(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_examen)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_examen->update($datos);
            $modelo = new TipoHabitoToxicoResource($tipo_examen->refresh());
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

    public function destroy(TipoHabitoToxicoRequest $request, TipoHabitoToxico $tipo_examen)
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
