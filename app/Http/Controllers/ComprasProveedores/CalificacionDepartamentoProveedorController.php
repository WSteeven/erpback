<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\CalificacionDepartamentoProveedorResource;
use App\Http\Resources\ComprasProveedores\DetalleDepartamentoProveedorResource;
use App\Models\ComprasProveedores\CalificacionDepartamentoProveedor;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\Proveedor;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class CalificacionDepartamentoProveedorController extends Controller
{
    private ArchivoService $archivoService;

    public function __construct()
    {
        $this->archivoService = new ArchivoService();
    }

    public function index()
    {
        $results = CalificacionDepartamentoProveedor::filter()->get();

        $results = CalificacionDepartamentoProveedorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws Throwable|ValidationException
     */
    public function store(Request $request)
    {
//        Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', $request->all()]);
        try {
            DB::beginTransaction();

            $modelos = [];
            $detalle = DetalleDepartamentoProveedor::where('departamento_id', auth()->user()->empleado->departamento_id)->where('proveedor_id', $request->proveedor_id)->first();
            if(!$detalle) throw new Exception('No se encontró un registro de departamento de calificación para proveedor para este empleado.');
            $modelo = $this->guardarCalificacionIndividual($request, $detalle);

            return response()->json(['mensaje' => 'Se crearon exitosamente las calificaciones', 'permisos' => $modelos, 'modelo' => $modelo]);
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', 'Ha ocurrido un error al insertar los registros', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al insertar la calificación del proveedor');
        }
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function guardarRecalificacion(Request $request)
    {
        try {
            DB::beginTransaction();

            $detalle = $request->detalle_departamento_proveedor_id? DetalleDepartamentoProveedor::find($request->detalle_departamento_proveedor_id): DetalleDepartamentoProveedor::where('departamento_id', auth()->user()->empleado->departamento_id)->where('proveedor_id',$request->proveedor_id)->orderBy('id', 'desc')->first();
            if(!$detalle) throw new Exception('No se encontró un registro de departamento de calificación para proveedor para este empleado.');
            $modelo = $this->guardarCalificacionIndividual($request, $detalle);

            return response()->json(['mensaje' => 'Se recalificó exitosamente al proveedor', 'modelo' => $modelo]);
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al recalificar el proveedor', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al insertar la recalificación del proveedor');
        }
    }

    public function indexFiles(int $detalle)
    {
        $results = [];
        try {
            $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($detalle_dept) {
                $results = $detalle_dept->archivos()->get();
            }

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'));
        }
    }


    public function storeFiles(Request $request, $detalle)
    {
        // Log::channel('testing')->info('Log', ['Recibido del front en storeFiles', $request->all(), $detalle]);
        $results = [];
        $mensaje = '';
        try {
            $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($detalle_dept) {
                $results = $this->archivoService->guardarArchivo($detalle_dept, $request->file, RutasStorage::CALIFICACIONES_PROVEEDORES->value);
                $mensaje = 'Archivo subido correctamente';
            }
            return response()->json(compact('mensaje', 'results'));
        } catch (Throwable $th) {
            $mensaje = $th->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Obtiene la calificación individual realizada por un empleado.
     * @param Request $request
     * @return JsonResponse
     */
    public function calificacionIndividualCompleta(Request $request)
    {
        $calificacion = DetalleDepartamentoProveedor::where('proveedor_id', $request->proveedor_id)
            ->where('departamento_id', $request->departamento_id)->first();

        $calificaciones = CalificacionDepartamentoProveedor::where('detalle_departamento_id', $calificacion->id)->get();

        $results['calificacion'] = new DetalleDepartamentoProveedorResource($calificacion);
        $results['calificaciones_detalladas'] = CalificacionDepartamentoProveedorResource::collection($calificaciones);
        return response()->json(compact('results'));
    }

    public function todasCalificacionIndividualesCompletas(Request $request)
    {
        $calificacion = DetalleDepartamentoProveedor::where('proveedor_id', $request->proveedor_id)
            ->where('departamento_id', $request->departamento_id)->get();
        $results = [];
        foreach ($calificacion as $cal) {
            $fecha = $cal->created_at->format('Y-m');

            $calificaciones = CalificacionDepartamentoProveedor::where('detalle_departamento_id', $cal->id)->get();
            if ($calificaciones->count() > 0) {
//                Log::channel('testing')->info('Log', ['todasCalificacionIndividualesCompletas', $calificaciones]);
                $results[$fecha]['calificacion'] = new DetalleDepartamentoProveedorResource($cal);
                $results[$fecha]['calificaciones_detalladas'] = CalificacionDepartamentoProveedorResource::collection($calificaciones);
            }
        }

//        return $results;
        return response()->json(compact('results'));
    }

    /**
     * @param Request $request
     * @param DetalleDepartamentoProveedor $detalle
     * @return DetalleDepartamentoProveedor|Model
     * @throws Throwable
     */
    private function guardarCalificacionIndividual(Request $request, DetalleDepartamentoProveedor $detalle): DetalleDepartamentoProveedor|Model
    {
        $datos = array_map(function ($calificacion) {
            return [
                'criterio_calificacion_id' => $calificacion['id'],
                'comentario' => array_key_exists('comentario', $calificacion) ? $calificacion['comentario'] : null,
                'peso' => $calificacion['peso'],
                'puntaje' => $calificacion['puntaje'],
                'calificacion' => $calificacion['calificacion']
            ];
        }, $request->criterios);
        $detalle->calificaciones_criterios()->sync($datos);

        DB::commit();

        //despues del commit se guarda la calificacion en el departamento
        $detalle->update([
            'calificacion' => $request->calificacion,
            'empleado_id' => auth()->user()->empleado->id,
            'fecha_calificacion' => date('Y-m-d H:i:s')
        ]);

        Proveedor::guardarCalificacion($request->proveedor_id); //Aquí se llama a la función para guardar la calificacion del proveedor
        return $detalle->refresh();
    }
}
