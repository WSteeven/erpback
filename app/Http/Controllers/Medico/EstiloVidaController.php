<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\EstiloVidaRequest;
use App\Http\Resources\Medico\EstiloVidaResource;
use App\Models\Medico\EstiloVida;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class EstiloVidaController extends Controller
{
    private $entidad = 'Estilo de vida';

    public function __construct()
    {
        $this->middleware('can:puede.ver.estilos_vida')->only('index', 'show');
        $this->middleware('can:puede.crear.estilos_vida')->only('store');
        $this->middleware('can:puede.editar.estilos_vida')->only('update');
        $this->middleware('can:puede.eliminar.estilos_vida')->only('destroy');
    }

    public function index()
    {
        $results = [];
        $results = EstiloVida::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    public function store(EstiloVidaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $estilo_vida = EstiloVida::create($datos);
            $modelo = new EstiloVidaResource($estilo_vida);
            $this->tabla_roles($estilo_vida);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function show(EstiloVidaRequest $request, EstiloVida $estilo_vida)
    {
        $modelo = new EstiloVidaResource($estilo_vida);
        return response()->json(compact('modelo'));
    }


    public function update(EstiloVidaRequest $request, EstiloVida $estilo_vida)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $estilo_vida->update($datos);
            $modelo = new EstiloVidaResource($estilo_vida->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    public function destroy(EstiloVidaRequest $request, EstiloVida $estilo_vida)
    {
        try {
            DB::beginTransaction();
            $estilo_vida->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de estilo de vida' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
