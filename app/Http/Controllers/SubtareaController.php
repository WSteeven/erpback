<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Empleado;
use App\Models\PausaSubtarea;
use App\Models\Subtarea;
use App\Models\Tarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\SubtareaService;
use Src\Shared\Utils;

class SubtareaController extends Controller
{
    private $entidad = 'Subtarea';
    private SubtareaService $servicio;

    public function __construct()
    {
        $this->servicio = new SubtareaService();
    }

    public function list()
    {
        // Obtener parametros
        $page = request('page');
        $offset = request('offset');

        // Procesar
        if ($page) return $this->servicio->obtenerPaginacion($offset);
        return $this->servicio->obtenerTodos();

        /*$filter = Subtarea::filter()->simplePaginate();
        SubtareaResource::collection($filter);
        return $filter;
        return SubtareaResource::collection(Empleado::filter()->get());*/
    }

    /**
     * Listar
     */
    public function index()
    {
        // Log::channel('testing')->info('Log', ['REQUEST RECIBIDA', request()]);
        $results = $this->list();
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(SubtareaRequest $request)
    {
        $tarea_id = $request['tarea_id'];

        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        $datos['codigo_subtarea'] = Tarea::find($tarea_id)->codigo_tarea . '-' . (Subtarea::where('tarea_id', $tarea_id)->count() + 1);

        $datos['fecha_hora_creacion'] = Carbon::now();

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;

        // Respuesta
        $modelo = Subtarea::create($datos);
        $modelo = new SubtareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Consultar
     */
    public function show(Subtarea $subtarea)
    {
        $modelo = new SubtareaResource($subtarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(SubtareaRequest $request, Subtarea $subtarea)
    {
        // Adaptacion de foreign keys
        $datos = $request->validated();
        $datos['grupo_id'] = $request->safe()->only(['grupo'])['grupo'];
        $datos['tipo_trabajo_id'] = $request->safe()->only(['tipo_trabajo'])['tipo_trabajo'];
        // -- $datos['cliente_id'] = $request->safe()->only(['cliente'])['cliente'];

        // Respuesta
        $subtarea->update($datos);
        $modelo = new SubtareaResource($subtarea->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Eliminar
     */
    public function destroy(Subtarea $subtarea)
    {
        $subtarea->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    // Estados de las subtareas
    public function asignar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::ASIGNADO;
        $subtarea->fecha_hora_asignacion = Carbon::now();
        $subtarea->save();
    }

    public function realizar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->save();
    }

    public function pausar(Request $request, Subtarea $subtarea)
    {
        $motivo = $request['motivo'];
        $subtarea->estado = Subtarea::PAUSADO;
        $subtarea->save();
        //$subtarea->fecha_hora_pa = Carbon::now();

        $subtarea->pausasSubtarea()->create([
            'fecha_hora_pausa' => Carbon::now(),
            'motivo' => $motivo,
        ]);
    }

    public function ejecutar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->fecha_hora_ejecucion = Carbon::now();
        $subtarea->save();
    }

    public function reanudar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::EJECUTANDO;
        $subtarea->save();

        $subtarea->pausasSubtarea()->update([
            'fecha_hora_retorno' => Carbon::now(),
        ]);
    }

    public function suspender(Request $request, Subtarea $subtarea)
    {
        $motivo = $request['motivo'];

        $subtarea->estado = Subtarea::SUSPENDIDO;
        $subtarea->fecha_hora_suspendido = Carbon::now();
        $subtarea->causa_suspencion = $motivo;
        $subtarea->save();
    }

    public function pausas(Subtarea $subtarea)
    {
        return response()->json(['results' => $subtarea->pausasSubtarea]);
    }
}
