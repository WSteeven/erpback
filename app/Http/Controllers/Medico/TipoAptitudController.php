<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\TipoAptitudRequest;
use App\Http\Resources\Medico\TipoAptitudResource;
use App\Models\Medico\TipoAptitud;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoAptitudController extends Controller
{
    private $entidad = 'Tipo de aptitud';

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_aptitudes')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_aptitudes')->only('store');
        $this->middleware('can:puede.editar.tipos_aptitudes')->only('update');
        $this->middleware('can:puede.eliminar.tipos_aptitudes')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = TipoAptitud::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(TipoAptitudRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $tipo_antecedente = TipoAptitud::create($datos);
            $modelo = new TipoAptitudResource($tipo_antecedente);
            $this->tabla_roles($tipo_antecedente);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(TipoAptitudRequest $request, TipoAptitud $tipo_antecedente)
    {
        $modelo = new TipoAptitudResource($tipo_antecedente);
        return response()->json(compact('modelo'));
    }


    public function update(TipoAptitudRequest $request, TipoAptitud $tipo_antecedente)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $tipo_antecedente->update($datos);
            $modelo = new TipoAptitudResource($tipo_antecedente->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(TipoAptitudRequest $request, TipoAptitud $tipo_antecedente)
    {
        try {
            DB::beginTransaction();
            $tipo_antecedente->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de tipo de aptitud' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
