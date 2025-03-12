<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\CalificacionDepartamentoProveedorResource;
use App\Http\Resources\ComprasProveedores\DetalleDepartamentoProveedorResource;
use App\Models\ComprasProveedores\CalificacionDepartamentoProveedor;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\Proveedor;
use Exception;
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
            $datos = array_map(function ($detalle) {
                return [
                    'criterio_calificacion_id' => $detalle['id'],
                    'comentario' => array_key_exists('comentario', $detalle) ? $detalle['comentario'] : null,
                    'peso' => $detalle['peso'],
                    'puntaje' => $detalle['puntaje'],
                    'calificacion' => $detalle['calificacion']
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

            // $proveedor = Proveedor::find($request->proveedor_id);
            Proveedor::guardarCalificacion($request->proveedor_id); //Aquí se llama a la función para guardar la calificacion del proveedor
            $modelo = $detalle->refresh();

            return response()->json(['mensaje' => 'Se crearon exitosamente las calificaciones', 'permisos' => $modelos, 'modelo' => $modelo]);
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', 'Ha ocurrido un error al insertar los registros', $e->getMessage(), $e->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($e, 'Problema al insertar la calificación del proveedor');
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

    public function calificacionIndividualCompleta(Request $request)
    {
        $calificacion = DetalleDepartamentoProveedor::where('proveedor_id', $request->proveedor_id)
            ->where('departamento_id', $request->departamento_id)->first();

        $calificaciones = CalificacionDepartamentoProveedor::where('detalle_departamento_id', $calificacion->id)->get();

        $results['calificacion'] = new DetalleDepartamentoProveedorResource($calificacion);
        $results['calificaciones_detalladas'] =CalificacionDepartamentoProveedorResource::collection($calificaciones);


        return response()->json(compact('results'));
    }


}
