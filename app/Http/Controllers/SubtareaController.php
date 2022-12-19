<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtareaRequest;
use App\Http\Resources\SubtareaResource;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\App\SubtareaService;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
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
        $estados = request('estados');
        $campos = explode(',', request('campos'));

        // Procesar
        if ($estados && request('campos')) return $this->servicio->obtenerFiltradosEstadosCampos($estados, $campos);
        elseif ($estados) return $this->servicio->obtenerFiltradosEstados($estados);
        if ($page) return $this->servicio->obtenerPaginacion($offset);
        return $this->servicio->obtenerTodos();
    }

    public function subtareasAsignadas()
    {
        // Obtener parametros
        $page = request('page');
        $offset = request('offset');

        // Procesar
        $empleado = User::find(Auth::id())->empleado;

        if ($page) return response()->json(['results' =>  $this->servicio->obtenerAsignadasPaginacion($empleado, $offset)]);
        return response()->json(['results' => $this->servicio->obtenerAsignadasTodos()]);
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
        $datos['coordinador_id'] = Auth::id();

        $datos['fecha_hora_creacion'] = Carbon::now();

        // Calcular estados
        $datos['estado'] = Subtarea::CREADO;



        // Respuesta
        // Log::channel('testing')->info('Log', ['Datos', $request->all()]);
        /* $subtareaEncontrada = Tarea::find($tarea_id)->subtareas()->where('fecha_hora_asignacion', '!=', null)->orderBy('fecha_hora_asignacion', 'asc')->first();
        if ($subtareaEncontrada && $subtareaEncontrada->estado === Subtarea::SUSPENDIDO) {
            return response()->json(['errors' => ['suspendido' => 'No se pueden agregar porque la subtarea principal está suspendida.']], 422);
        }

        if ($subtareaEncontrada && $subtareaEncontrada->estado === Subtarea::CANCELADO) {
            return response()->json(['errors' => ['cancelada' => 'No se pueden agregar porque la subtarea principal está cancelada.']], 422);
        } */

        $modelo = Subtarea::create($datos);

        $tecnicosGrupoPrincipal = $request->safe()->only(['tecnicos_grupo_principal'])['tecnicos_grupo_principal'];
        if ($tecnicosGrupoPrincipal) {
            $tecnicosGrupoPrincipal = Utils::quitarEspaciosComasString($tecnicosGrupoPrincipal);
            $tecnicosGrupoPrincipal = Utils::convertirStringComasArray($tecnicosGrupoPrincipal);

            // Guardar id de tecnicos
            if (count($tecnicosGrupoPrincipal)) $modelo->empleados()->sync($tecnicosGrupoPrincipal);
        }

        /* $tecnicosOtrosGrupos = $request->safe()->only(['tecnicos_otros_grupos'])['tecnicos_otros_grupos'];
        if ($tecnicosOtrosGrupos) {
            $tecnicosOtrosGrupos = str_replace(', ', '', $tecnicosOtrosGrupos);
            $tecnicosOtrosGrupos = explode(',', $tecnicosOtrosGrupos);

            Log::channel('testing')->info('Log', ['Otro grupo', $tecnicosOtrosGrupos]);
            Log::channel('testing')->info('Log', ['Otro grupo count', count($tecnicosOtrosGrupos)]);

            if (count($tecnicosOtrosGrupos)) $modelo->empleados()->attach($tecnicosOtrosGrupos);
        } */

        $modelo = new SubtareaResource($modelo->refresh());
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

        $tecnicosGrupoPrincipal = $request->safe()->only(['tecnicos_grupo_principal'])['tecnicos_grupo_principal'];
        if ($tecnicosGrupoPrincipal) {
            $tecnicosGrupoPrincipal = Utils::quitarEspaciosComasString($tecnicosGrupoPrincipal);
            $tecnicosGrupoPrincipal = Utils::convertirStringComasArray($tecnicosGrupoPrincipal);

            // Guardar id de tecnicos
            if (count($tecnicosGrupoPrincipal)) $modelo->empleados()->sync($tecnicosGrupoPrincipal);
        } else {
            $modelo->empleados()->sync([]);
        }

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

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function realizar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::REALIZADO;
        $subtarea->fecha_hora_realizado = Carbon::now();
        $subtarea->save();

        return response()->json(['modelo' => $subtarea->refresh()]);
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

        return response()->json(['modelo' => $subtarea->refresh()]);
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

        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function pausas(Subtarea $subtarea)
    {
        return response()->json(['results' => $subtarea->pausasSubtarea]);
    }

    public function cancelar(Subtarea $subtarea)
    {
        $subtarea->estado = Subtarea::CANCELADO;
        $subtarea->fecha_hora_cancelacion = Carbon::now();
        $subtarea->save();
        return response()->json(['modelo' => $subtarea->refresh()]);
    }

    public function subirArchivo(Request $request)
    {
        // Guardar archivo
        $subtarea = Subtarea::find($request['subtarea']);

        if ($subtarea && $request->hasFile('file')) {

            $guardarArchivo = new GuardarArchivo($subtarea, $request, RutasStorage::SUBTAREAS);
            $guardarArchivo->execute();

            return response()->json(['mensaje' => 'Subido exitosamente!']);
        }

        return response()->json(['mensaje' => 'No se pudo subir!']);
    }
}
