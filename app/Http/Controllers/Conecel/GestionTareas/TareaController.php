<?php

namespace App\Http\Controllers\Conecel\GestionTareas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Conecel\GestionTareas\TareaResource;
use App\Models\Conecel\GestionTareas\Tarea;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class TareaController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:puede.ver.tareas_conecel')->only('index', 'show');
        $this->middleware('can:puede.crear.tareas_conecel')->except('store');
        $this->middleware('can:puede.editar.tareas_conecel')->except('update');
        $this->middleware('can:puede.eliminar.tareas_conecel')->except('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = (request('astatus') == 'TODAS') ? Tarea::orderBy('id', 'desc')->get() : Tarea::filter()->orderBy('id', 'desc')->get();
        $results = TareaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Tarea $tarea
     * @return JsonResponse
     */
    public function show(Tarea $tarea)
    {
        $modelo = new TareaResource($tarea);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error' => Utils::metodoNoDesarrollado()]);
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    /*public function subirTareasLotes(Request $request, $grupo_id)
    {
        try {
            DB::beginTransaction();
            $this->validate($request, [
                'file' => 'required|mimetypes:text/plain,text/csv,application/vnd.ms-excel'
            ]);
            if (!$request->hasFile('file')) {
                throw ValidationException::withMessages([
                    'file' => ['Debe seleccionar al menos un archivo.'],
                ]);
            }

            Excel::import(new ClaroTareasImport($request->file->getClientOriginalName(), $grupo_id), $request->file);
            $mensaje = 'Archivo subido exitosamente!';
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->error('Log', ['ERROR al leer el archivo', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'file' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }*/


    public function actividadesOFSClaro(Request $request)
    {
//        Log::channel('testing')->info('Log', ['Recibido en la request actividadesOFSClaro', $request->all()]);

        try {

            $data = $request->all();

            // Validar que venga el array de actividades
            if (!isset($data['data']['activitiesRows']) || !is_array($data['data']['activitiesRows'])) {
                Log::channel('testing')->error('Log', ['Formato de datos invalido', 400, $data['source']]);
//            return response()->json(['error' => 'Formato de datos inválido'], 400);
            }

            $source = $data['source'] ?? 'unknown';
            $activities = $data['data']['activitiesRows'];
            $savedCount = 0;

            foreach ($activities as $act) {
                $aid = $act['aid'] ?? null;
                if (!$aid) continue;

                $aworktype = $act['aworktype'] ?? null;
                if (in_array($aworktype, ['Almuerzo', 'Bodega Inicio Día', 'Bodega Fin Día'])) {
                    continue; // No insertar estos tipos
                }

                // Extraer coordenadas
                $lat = $act['_v']['y'] ?? null;
                $lng = $act['_v']['x'] ?? null;

                // Limpiar email (quitar HTML)
                $email = strip_tags($act['cemail'] ?? '');

                Tarea::updateOrCreate(
                    ['aid' => $aid], // Clave única
                    [
                        'source' => $source,
                        'time_slot' => $act['time_slot'] ?? null,
                        'eta' => $act['ETA'] ?? null,
                        'end_time' => $act['end_time'] ?? null,
                        'aworktype' => $aworktype,
                        'appt_number' => $act['appt_number'] ?? null,
                        'cname' => $act['cname'] ?? null,
                        'activity_workskills' => $act['activity_workskills'] ?? null,
                        'aworkzone' => $act['aworkzone'] ?? null,
                        'direccion' => $act['-62332'] ?? null,
                        'cemail' => $email,
                        'astatus' => $act['astatus'] ?? null,
                        'atime_of_booking' => $act['atime_of_booking'] ?? null,
                        'atime_of_assignment' => $act['atime_of_assignment'] ?? null,
                        'lat' => $lat,
                        'lng' => $lng,
                        'duration' => $act['_v']['d'] ?? null,
                        'travel_time' => $act['_v']['G'] ?? null,
                        'sla' => $act['_v']['S'] ?? null,
                        'raw_data' => $act,
                        'received_at' => now(),
                    ]
                );

                $savedCount++;
            }

            $mensaje = "Datos recibidos y almacenados con éxito. $savedCount actividades procesadas.";
//            Log::channel('testing')->info('Log', ['Mensaje actividadesOFSClaro', $mensaje]);
            return response()->json(compact('mensaje', 'savedCount'));

        } catch (Exception $e) {
            Log::channel('testing')->error('Log', ['Excepcion', $e->getMessage(), $e->getLine()]);
            return response()->json(['Hubo error']);
        }
    }
}
