<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RespuestaRequest;
use App\Http\Resources\Medico\RespuestaResource;
use App\Models\Medico\Respuesta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RespuestaController extends Controller
{
    private $entidad = 'Respuesta';

    public function __construct()
    {
        $this->middleware('can:puede.ver.respuestas')->only('index', 'show');
        $this->middleware('can:puede.crear.respuestas')->only('store');
        $this->middleware('can:puede.editar.respuestas')->only('update');
        $this->middleware('can:puede.eliminar.respuestas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Respuesta::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\RespuestaRequest  $respuestarequest
     * @return \Illuminate\Http\Response
     */
    public function store(RespuestaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $respuesta = Respuesta::create($datos);
            $modelo = new RespuestaResource($respuesta);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de respuesta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function show(Respuesta $respuesta)
    {
        $modelo = new RespuestaResource($respuesta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\RespuestaRequest  $respuestarequest
     * @param  Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function update(RespuestaRequest $request, Respuesta $respuesta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $respuesta->update($datos);
            $modelo = new RespuestaResource($respuesta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de respuesta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Respuesta  $respuesta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Respuesta $respuesta)
    {
        try {
            DB::beginTransaction();
            $respuesta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de respuesta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
