<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RecetaRequest;
use App\Http\Resources\Medico\RecetaResource;
use App\Models\Medico\Receta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RecetaController extends Controller
{
    private $entidad ='Receta';

    public function __construct()
    {
        $this->middleware('can:puede.ver.recetas')->only('index', 'show');
        $this->middleware('can:puede.crear.recetas')->only('store');
        $this->middleware('can:puede.editar.recetas')->only('update');
        $this->middleware('can:puede.eliminar.recetas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Receta::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RecetaRequest  $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $receta = Receta::create($datos);
            $modelo = new RecetaResource($receta);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de receta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function show(Receta $receta)
    {
        $modelo = new RecetaResource($receta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function update(RecetaRequest $request,Receta $receta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $receta->update($datos);
            $modelo = new RecetaResource($receta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de receta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Receta  $receta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receta $receta)
    {
        try {
            DB::beginTransaction();
            $receta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de receta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
