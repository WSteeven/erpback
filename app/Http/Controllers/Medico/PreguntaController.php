<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\PreguntaRequest;
use App\Http\Resources\Medico\PreguntaResource;
use App\Models\Medico\Pregunta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class PreguntaController extends Controller
{
    private $entidad = 'Pregunta';

    public function __construct()
    {
        $this->middleware('can:puede.ver.preguntas')->only('index', 'show');
        $this->middleware('can:puede.crear.preguntas')->only('store');
        $this->middleware('can:puede.editar.preguntas')->only('update');
        $this->middleware('can:puede.eliminar.preguntas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Pregunta::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\PreguntaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PreguntaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $pregunta = Pregunta::create($datos);
            $modelo = new PreguntaResource($pregunta);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de pregunta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function show(Pregunta $pregunta)
    {
        $modelo = new PreguntaResource($pregunta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PreguntaRequest  $preguntarequest
     * @param  Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function update(PreguntaRequest $request,Pregunta $pregunta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $pregunta->update($datos);
            $modelo = new PreguntaResource($pregunta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de pregunta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Pregunta  $pregunta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pregunta$pregunta)
    {
        try {
            DB::beginTransaction();
            $pregunta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de pregunta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
